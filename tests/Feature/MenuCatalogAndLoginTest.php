<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuCatalogAndLoginTest extends TestCase
{
    public function test_menu_catalog_defaults_to_active_branch_menus(): void
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
        // Should show 9 menus of SajiHUB Jakarta Selatan (first branch), not 36
        $response->assertSee('Menampilkan 9 Menu');
        $response->assertSee('SajiHUB Jakarta Selatan');
    }

    public function test_menu_catalog_can_still_filter_all_branches(): void
    {
        $response = $this->get('/menu?branch_id=all');
        $totalMenus = \App\Models\Menu::count();
        $response->assertSee("Menampilkan {$totalMenus} Menu");
    }

    public function test_menu_catalog_follows_logged_in_admin_branch(): void
    {
        $admin = User::where('email', 'admin_jakarta@sajihub.com')->first();
        if ($admin) {
            $response = $this->actingAs($admin)->get('/menu');
            $response->assertStatus(200);
            $response->assertSee('Menampilkan 9 Menu');
        }
    }

    public function test_login_page_has_proper_password_toggle_and_css(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('images/eye-open.png', false);
        $response->assertSee('images/eye-closed.png', false);
        $response->assertSee('x-data="{ show: false }"', false);
        $response->assertSee(':type="show ? \'text\' : \'password\'"', false);
        $response->assertSee('tabindex="-1"', false);
        $response->assertSee('type="button"', false);
        $response->assertSee('input[type="password"]::-ms-reveal', false);
    }

    public function test_menu_catalog_search_does_not_throw_sql_error(): void
    {
        $response = $this->get('/menu?search=ayam&branch_id=11&sort=popular');
        $response->assertStatus(200);
        $response->assertSee('catalog-search-input', false);
    }

    public function test_logout_modal_styling_and_clean_text(): void
    {
        $admin = User::where('email', 'admin_jakarta@sajihub.com')->first();
        if ($admin) {
            $response = $this->actingAs($admin)->get('/admin/dashboard');
            $response->assertStatus(200);
            $response->assertSee('Konfirmasi Keluar Akun');
            $response->assertSee('Apakah Anda yakin ingin keluar dari sistem SajiHUB?');
            // The old user profile card with user name in square box should not exist
            $response->assertDontSee('<!-- User Info Profile Card -->', false);
        }
    }
}
