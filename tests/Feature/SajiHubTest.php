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

    public function test_customer_can_register_using_email()
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Registration Email Test',
            'username_or_email' => 'testreg@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'pelanggan',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'Registration Email Test',
            'email' => 'testreg@example.com',
            'username' => 'testreg',
            'phone' => '081234567890',
            'role' => 'pelanggan',
        ]);
    }

    public function test_customer_can_register_using_username()
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Registration Username Test',
            'username_or_email' => 'regusername',
            'phone' => '081234567891',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'member',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'Registration Username Test',
            'username' => 'regusername',
            'email' => 'regusername@sajihub.local',
            'phone' => '081234567891',
            'role' => 'member',
        ]);
    }
}
