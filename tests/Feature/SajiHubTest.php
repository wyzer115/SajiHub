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
}
