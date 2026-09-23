<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SajiHubTest extends TestCase
{
    use DatabaseTransactions;

    public function test_customer_order_mapping_parameter()
    {
        $branch = Branch::create([
            'name' => 'SajiHUB Test Branch',
            'address' => 'Test address',
        ]);

        $table = Table::create([
            'branch_id' => $branch->id,
            'table_number' => '05',
            'status' => 'empty',
        ]);

        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'username' => 'customer_test',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($customer)
            ->get(route('order.qr', ['branch_id' => $branch->id, 'table' => '05']));

        $response->assertStatus(200);
        $response->assertViewHas('selectedBranch');
        $response->assertViewHas('selectedTable');
        
        $viewTable = $response->original->getData()['selectedTable'];
        $this->assertEquals($table->id, $viewTable->id);
    }

    public function test_superadmin_user_hierarchy_role_access()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'username' => 'superadmin_test',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $adminCabang = User::create([
            'name' => 'Admin Cabang',
            'email' => 'admin@test.com',
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin_cabang',
        ]);

        // Superadmin should be able to access the admin user list
        $response = $this->actingAs($superadmin)->get(route('superadmin.users.index'));
        $response->assertStatus(200);

        // Admin Cabang should be forbidden (403) from accessing superadmin users list
        $response = $this->actingAs($adminCabang)->get(route('superadmin.users.index'));
        $response->assertStatus(403);
    }

    public function test_kasir_order_creation_blocked_when_branch_is_closed()
    {
        $branch = Branch::create([
            'name' => 'Closed Branch',
            'address' => 'Test Address',
            'status' => 'tutup',
            'status_note' => 'Dalam Renovasi',
        ]);

        $kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@test.com',
            'username' => 'kasir_test',
            'password' => bcrypt('password'),
            'role' => 'kasir',
            'branch_id' => $branch->id,
        ]);

        $response = $this->actingAs($kasir)
            ->get(route('kasir.orders.create'));

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Sistem Kasir Dikunci: Cabang sedang ditutup oleh Pusat. Catatan: Dalam Renovasi');
    }

    public function test_admin_cabang_can_crud_waiter_employee()
    {
        $branch = Branch::create([
            'name' => 'SajiHUB South Jakarta',
            'address' => 'Jakarta Selatan',
        ]);

        $adminCabang = User::create([
            'name' => 'Admin Cabang',
            'email' => 'admin_s@test.com',
            'username' => 'admin_s_test',
            'password' => bcrypt('password'),
            'role' => 'admin_cabang',
            'branch_id' => $branch->id,
        ]);

        $response = $this->actingAs($adminCabang)
            ->post(route('admin.users.store'), [
                'name' => 'Waiter Udin',
                'email' => 'udin@waiter.com',
                'username' => 'udin_waiter',
                'password' => 'password123',
                'role' => 'waiter',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'username' => 'udin_waiter',
            'role' => 'waiter',
            'branch_id' => $branch->id,
        ]);

        $responseList = $this->actingAs($adminCabang)
            ->get(route('admin.users.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee('Waiter Udin');
    }

    public function test_impersonator_cannot_modify_branch_data()
    {
        $branch = Branch::create([
            'name' => 'Impersonate Test Branch',
            'address' => 'Test Address',
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'username' => 'super_test',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $adminCabang = User::create([
            'name' => 'Admin Cabang',
            'email' => 'admin_c@test.com',
            'username' => 'admin_c_test',
            'password' => bcrypt('password'),
            'role' => 'admin_cabang',
            'branch_id' => $branch->id,
        ]);

        $response = $this->actingAs($adminCabang)
            ->withSession(['impersonator_id' => $superAdmin->id])
            ->post(route('admin.users.store'), [
                'name' => 'New Waiter',
                'email' => 'waiter_new@test.com',
                'username' => 'new_waiter',
                'password' => 'password123',
                'role' => 'waiter',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Mode Intip Aktif: Anda hanya diperbolehkan memantau dasbor dan tidak diizinkan mengubah atau menambah data.');

        $this->assertDatabaseMissing('users', [
            'username' => 'new_waiter',
        ]);
    }

    public function test_stock_auto_deduction_on_order()
    {
        $branch = Branch::first();
        $table = Table::where('branch_id', $branch->id)->first();
        $menu = \App\Models\Menu::where('branch_id', $branch->id)->where('name', 'Mie Goreng Seafood')->first();
        $this->assertNotNull($menu, 'Mie Goreng Seafood menu should exist');

        $mieIngredient = \App\Models\MenuIngredient::where('menu_id', $menu->id)
            ->whereHas('inventory', fn($q) => $q->where('name', 'Mie Basah & Seafood'))
            ->first();
        $this->assertNotNull($mieIngredient, 'Mie ingredient should exist in recipe');

        $inventory = $mieIngredient->inventory;
        $initialStock = (float) $inventory->stock;

        $customer = User::firstOrCreate(
            ['username' => 'test_customer_stock'],
            [
                'name' => 'Test Customer Stock',
                'email' => 'customer_stock@test.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
            ]
        );

        $orderQty = 2;
        $expectedDeduction = (float)$mieIngredient->quantity * $orderQty; // 0.20 * 2 = 0.40

        $response = $this->actingAs($customer)->post(route('pesan.store'), [
            'branch_id' => $branch->id,
            'table_id' => $table->id,
            'customer_name' => 'Budi Stock Test',
            'payment_method' => 'cash',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => $orderQty,
                    'notes' => 'Pedas sedang'
                ]
            ]
        ]);

        $response->assertStatus(302);
        
        $inventory->refresh();
        $this->assertEquals(round($initialStock - $expectedDeduction, 2), (float)$inventory->stock);
    }

    public function test_cashier_order_stock_deduction()
    {
        $branch = Branch::first();
        $table = Table::where('branch_id', $branch->id)->first();
        $menu = \App\Models\Menu::where('branch_id', $branch->id)->where('name', 'French Fries')->first();
        $this->assertNotNull($menu);

        $kentangIng = \App\Models\MenuIngredient::where('menu_id', $menu->id)
            ->whereHas('inventory', fn($q) => $q->where('name', 'Kentang Potong Beku'))
            ->first();
        $this->assertNotNull($kentangIng);

        $inventory = $kentangIng->inventory;
        $initialStock = (float) $inventory->stock;

        $kasir = User::where('branch_id', $branch->id)->where('role', 'kasir')->first();
        $this->assertNotNull($kasir);

        $orderQty = 3;
        $expectedDeduction = (float)$kentangIng->quantity * $orderQty; // 0.15 * 3 = 0.45

        $response = $this->actingAs($kasir)->postJson(route('kasir.orders.store'), [
            'customer_name' => 'Walk-in Guest',
            'payment_method' => 'cash',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => $orderQty,
                    'notes' => 'Extra sauce'
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inventory->refresh();
        $this->assertEquals(round($initialStock - $expectedDeduction, 2), (float)$inventory->stock);
    }

    public function test_stock_cannot_become_negative()
    {
        $branch = Branch::first();
        $table = Table::where('branch_id', $branch->id)->first();
        $menu = \App\Models\Menu::where('branch_id', $branch->id)->where('name', 'Kopi Hitam Spesial')->first();
        $this->assertNotNull($menu);

        $kopiIng = \App\Models\MenuIngredient::where('menu_id', $menu->id)
            ->whereHas('inventory', fn($q) => $q->where('name', 'Bubuk Kopi Robusta'))
            ->first();
        $this->assertNotNull($kopiIng);

        // Set low stock
        $kopiIng->inventory->update(['stock' => 0.01]);

        $customer = User::firstOrCreate(
            ['username' => 'test_customer_neg'],
            [
                'name' => 'Test Customer Neg',
                'email' => 'customer_neg@test.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
            ]
        );

        // Order 1 cup which requires 0.02 kg (more than 0.01 kg in stock)
        $response = $this->actingAs($customer)->post(route('pesan.store'), [
            'branch_id' => $branch->id,
            'table_id' => $table->id,
            'customer_name' => 'Zero Stock Test',
            'payment_method' => 'cash',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => 1,
                    'notes' => ''
                ]
            ]
        ]);

        $response->assertStatus(302);

        $kopiIng->inventory->refresh();
        // Stock should be clamped to 0, not -0.01
        $this->assertEquals(0, (float)$kopiIng->inventory->stock);
    }

    public function test_kasir_qr_lookup_and_cash_confirmation()
    {
        $branch = Branch::first();
        $table = Table::where('branch_id', $branch->id)->first();
        $kasir = User::where('branch_id', $branch->id)->where('role', 'kasir')->first();
        $menu = \App\Models\Menu::where('branch_id', $branch->id)->first();

        // Create dine-in pending order
        $order = \App\Models\Order::create([
            'branch_id' => $branch->id,
            'table_id' => $table->id,
            'customer_name' => 'Test Dine-in Guest',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'total_price' => $menu->price,
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => 1,
            'price' => $menu->price,
        ]);

        // 1. Kasir scans QR code / looks up order
        $lookupRes = $this->actingAs($kasir)->postJson(route('kasir.orders.lookup'), [
            'code' => 'SAJI-ORD-' . $order->id
        ]);

        $lookupRes->assertStatus(200);
        $lookupRes->assertJsonPath('success', true);
        $lookupRes->assertJsonPath('order.id', $order->id);

        // 2. Kasir confirms cash payment
        $confirmRes = $this->actingAs($kasir)->postJson(route('kasir.orders.confirm-payment', $order->id), [
            'payment_method' => 'cash',
            'cash_paid' => $menu->price + 10000,
        ]);

        $confirmRes->assertStatus(200);
        $confirmRes->assertJsonPath('success', true);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('cooking', $order->order_status);
    }

    public function test_kasir_qris_confirmation_with_proof()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $branch = Branch::first();
        $table = Table::where('branch_id', $branch->id)->first();
        $kasir = User::where('branch_id', $branch->id)->where('role', 'kasir')->first();
        $menu = \App\Models\Menu::where('branch_id', $branch->id)->first();

        $order = \App\Models\Order::create([
            'branch_id' => $branch->id,
            'table_id' => $table->id,
            'customer_name' => 'QRIS Guest',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_price' => $menu->price,
        ]);

        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('bukti_tf.jpg');

        $confirmRes = $this->actingAs($kasir)->postJson(route('kasir.orders.confirm-payment', $order->id), [
            'payment_method' => 'qris',
            'payment_proof' => $fakeImage,
        ]);

        $confirmRes->assertStatus(200);
        $confirmRes->assertJsonPath('success', true);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->transaction);
        $this->assertEquals('qris', $order->transaction->payment_method);
        $this->assertNotNull($order->transaction->payment_proof);
    }
}
