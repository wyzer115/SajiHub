<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminMaintenanceTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;
    protected User $kasirUser;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::firstOrCreate(
            ['name' => 'Cabang Jakarta'],
            [
                'address' => 'Jl. Sudirman No. 1',
                'phone'   => '08123456789',
                'status'  => 'buka',
            ]
        );

        $this->superAdmin = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@sajihub.com',
                'password' => Hash::make('password'),
                'role'     => 'superadmin',
            ]
        );

        $this->kasirUser = User::firstOrCreate(
            ['username' => 'kasir_jkt'],
            [
                'name'      => 'Kasir Jakarta',
                'email'     => 'kasir@sajihub.com',
                'password'  => Hash::make('password'),
                'role'      => 'kasir',
                'branch_id' => $this->branch->id,
            ]
        );
    }

    public function test_public_pages_accessible_when_maintenance_is_off(): void
    {
        SystemSetting::set('maintenance_mode', false);

        $response = $this->get('/');
        $response->assertStatus(200);

        $responseMenu = $this->get('/menu');
        $responseMenu->assertStatus(200);
    }

    public function test_superadmin_can_access_maintenance_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.maintenance.index'));
        $response->assertStatus(200);
        $response->assertSee('Mode Pemeliharaan Website');
    }

    public function test_non_superadmin_cannot_access_maintenance_management(): void
    {
        $response = $this->actingAs($this->kasirUser)->get(route('superadmin.maintenance.index'));
        $response->assertStatus(403);
    }

    public function test_superadmin_can_toggle_maintenance_mode(): void
    {
        $this->assertFalse(SystemSetting::isMaintenanceMode());

        // Toggle ON
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.maintenance.toggle'));

        $response->assertRedirect();
        $this->assertTrue(SystemSetting::isMaintenanceMode());

        // Toggle OFF
        $response2 = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.maintenance.toggle'));

        $response2->assertRedirect();
        $this->assertFalse(SystemSetting::isMaintenanceMode());
    }

    public function test_superadmin_can_update_maintenance_settings(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.maintenance.update'), [
                'maintenance_mode'     => '1',
                'maintenance_title'    => 'Pembaruan Server SajiHub',
                'maintenance_message'  => 'Kami sedang upgrade database.',
                'maintenance_end_time' => now()->addHours(2)->toDateTimeString(),
            ]);

        $response->assertRedirect();
        $this->assertTrue(SystemSetting::isMaintenanceMode());
        $this->assertEquals('Pembaruan Server SajiHub', SystemSetting::get('maintenance_title'));
        $this->assertEquals('Kami sedang upgrade database.', SystemSetting::get('maintenance_message'));
    }

    public function test_public_and_staff_blocked_with_503_when_maintenance_is_on(): void
    {
        SystemSetting::set('maintenance_mode', true);
        SystemSetting::set('maintenance_title', 'Sistem Sedang Pemeliharaan');

        // Public visitor gets 503
        $response = $this->get('/');
        $response->assertStatus(503);
        $response->assertSee('Sistem Sedang Pemeliharaan');

        // Customer order page gets 503
        $responseOrder = $this->get('/pesan');
        $responseOrder->assertStatus(503);

        // JSON / API requests get JSON 503
        $responseJson = $this->getJson('/pesan');
        $responseJson->assertStatus(503);
        $responseJson->assertJsonFragment(['maintenance' => true]);
    }

    public function test_superadmin_retains_full_access_during_maintenance(): void
    {
        SystemSetting::set('maintenance_mode', true);

        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.dashboard'));
        $response->assertStatus(200);

        $responseIndex = $this->actingAs($this->superAdmin)->get(route('superadmin.maintenance.index'));
        $responseIndex->assertStatus(200);
    }

    public function test_non_superadmin_login_is_rejected_during_maintenance(): void
    {
        SystemSetting::set('maintenance_mode', true);

        // Kasir attempt
        $response = $this->post(route('login.post'), [
            'login'    => 'kasir_jkt',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();

        // Superadmin attempt succeeds
        $responseSuper = $this->post(route('login.post'), [
            'login'    => 'superadmin',
            'password' => 'password',
        ]);

        $responseSuper->assertRedirect(route('superadmin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin);
    }

    public function test_secret_bypass_token_allows_access_during_maintenance(): void
    {
        SystemSetting::set('maintenance_mode', true);
        $secret = 'test_secret_token_12345678';
        SystemSetting::set('maintenance_secret', $secret);

        // Accessing bypass route with secret
        $response = $this->get(route('maintenance.bypass', ['token' => $secret]));
        $response->assertRedirect(route('login'));
        $response->assertCookie('sajihub_maintenance_bypass', $secret);

        // Accessing public page with bypass cookie
        $responsePublic = $this->withCookie('sajihub_maintenance_bypass', $secret)->get('/');
        $responsePublic->assertStatus(200);
    }

    public function test_preview_mode_returns_200_for_superadmin(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.maintenance.preview'));
        $response->assertStatus(200);
        $response->assertSee('Mode Pratinjau');
    }
}
