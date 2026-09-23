<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Super Admin
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@sajihub.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // Owner Utama (Pemilik Bisnis Multi-Cabang)
        User::firstOrCreate(
            ['username' => 'owner'],
            [
                'name'      => 'Owner SajiHUB',
                'email'     => 'owner@sajihub.com',
                'password'  => Hash::make('password'),
                'role'      => 'owner',
                'branch_id' => null,
            ]
        );

        $branchesData = [
            [
                'key'        => 'jakarta',
                'name'       => 'SajiHUB Jakarta Selatan',
                'address'    => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'phone'      => '021-7891234',
                'admin'      => ['name' => 'Admin Jakarta', 'username' => 'admin_jakarta', 'email' => 'admin@sajihub.com'],
                'owner'      => ['name' => 'Owner Jakarta', 'username' => 'owner_jkt', 'email' => 'owner.jakarta@sajihub.com'],
                'supervisor' => ['name' => 'Supervisor Jakarta', 'username' => 'spv_jkt', 'email' => 'spv.jakarta@sajihub.com'],
                'kasir'      => ['name' => 'Kasir Jakarta', 'username' => 'kasir_jkt', 'email' => 'kasir@sajihub.com'],
                'dapur'      => ['name' => 'Dapur Jakarta', 'username' => 'koki_jkt', 'email' => 'koki@sajihub.com'],
            ],
            [
                'key'        => 'bandung',
                'name'       => 'SajiHUB Bandung',
                'address'    => 'Jl. Braga No. 45, Bandung',
                'phone'      => '022-4201234',
                'admin'      => ['name' => 'Admin Bandung', 'username' => 'admin_bandung', 'email' => 'bandung@sajihub.com'],
                'owner'      => ['name' => 'Owner Bandung', 'username' => 'owner_bdg', 'email' => 'owner.bandung@sajihub.com'],
                'supervisor' => ['name' => 'Supervisor Bandung', 'username' => 'spv_bdg', 'email' => 'spv.bandung@sajihub.com'],
                'kasir'      => ['name' => 'Kasir Bandung', 'username' => 'kasir_bdg', 'email' => 'kasir.bandung@sajihub.com'],
                'dapur'      => ['name' => 'Dapur Bandung', 'username' => 'koki_bdg', 'email' => 'koki.bandung@sajihub.com'],
            ],
            [
                'key'        => 'surabaya',
                'name'       => 'SajiHUB Surabaya',
                'address'    => 'Jl. Tunjungan No. 78, Surabaya',
                'phone'      => '031-5311234',
                'admin'      => ['name' => 'Admin Surabaya', 'username' => 'admin_surabaya', 'email' => 'surabaya@sajihub.com'],
                'owner'      => ['name' => 'Owner Surabaya', 'username' => 'owner_sby', 'email' => 'owner.surabaya@sajihub.com'],
                'supervisor' => ['name' => 'Supervisor Surabaya', 'username' => 'spv_sby', 'email' => 'spv.surabaya@sajihub.com'],
                'kasir'      => ['name' => 'Kasir Surabaya', 'username' => 'kasir_sby', 'email' => 'kasir.surabaya@sajihub.com'],
                'dapur'      => ['name' => 'Dapur Surabaya', 'username' => 'koki_sby', 'email' => 'koki.surabaya@sajihub.com'],
            ],
        ];

        foreach ($branchesData as $data) {
            $branch = Branch::firstOrCreate(
                ['name' => $data['name']],
                [
                    'address' => $data['address'],
                    'phone'   => $data['phone'],
                ]
            );

            // Admin Cabang
            User::firstOrCreate(
                ['username' => $data['admin']['username']],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['admin']['name'],
                    'email'     => $data['admin']['email'],
                    'password'  => Hash::make('password'),
                    'role'      => 'admin_cabang',
                ]
            );

            // Owner
            User::firstOrCreate(
                ['username' => $data['owner']['username']],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['owner']['name'],
                    'email'     => $data['owner']['email'],
                    'password'  => Hash::make('password'),
                    'role'      => 'owner',
                ]
            );

            // Supervisor
            User::firstOrCreate(
                ['username' => $data['supervisor']['username']],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['supervisor']['name'],
                    'email'     => $data['supervisor']['email'],
                    'password'  => Hash::make('password'),
                    'role'      => 'supervisor',
                ]
            );

            // Kasir
            User::firstOrCreate(
                ['username' => $data['kasir']['username']],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['kasir']['name'],
                    'email'     => $data['kasir']['email'],
                    'password'  => Hash::make('password'),
                    'role'      => 'kasir',
                ]
            );

            // Dapur
            User::firstOrCreate(
                ['username' => $data['dapur']['username']],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['dapur']['name'],
                    'email'     => $data['dapur']['email'],
                    'password'  => Hash::make('password'),
                    'role'      => 'dapur',
                ]
            );

            $catMakanan = Category::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Makanan']);
            $catMinuman = Category::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Minuman']);

            for ($i = 1; $i <= 4; $i++) {
                Table::firstOrCreate(
                    ['branch_id' => $branch->id, 'table_number' => "Table {$i}"],
                    [
                        'qr_code_token' => Str::random(32),
                        'status' => 'empty',
                    ]
                );
            }

            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Nasi Goreng Spesial'], ['category_id' => $catMakanan->id, 'price' => 35000, 'image' => 'images/landing/nasi-goreng.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Mie Goreng Seafood'], ['category_id' => $catMakanan->id, 'price' => 38000, 'image' => 'images/landing/mie-goreng.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Ayam Bakar Madu'], ['category_id' => $catMakanan->id, 'price' => 42000, 'image' => 'images/landing/ayam-bakar.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Sate Ayam'], ['category_id' => $catMakanan->id, 'price' => 30000, 'image' => 'images/landing/sate.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'French Fries'], ['category_id' => $catMakanan->id, 'price' => 18000, 'image' => 'images/landing/french-fries.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Pisang Goreng Keju'], ['category_id' => $catMakanan->id, 'price' => 20000, 'image' => 'images/landing/pisang-goreng.jpg']);

            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Es Teh Manis'], ['category_id' => $catMinuman->id, 'price' => 8000, 'image' => 'images/landing/es-teh.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Jus Alpukat'], ['category_id' => $catMinuman->id, 'price' => 18000, 'image' => 'images/landing/jus-alpukat.jpg']);
            Menu::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Kopi Hitam Spesial'], ['category_id' => $catMinuman->id, 'price' => 15000, 'image' => 'images/landing/kopi-hitam.jpg']);

            // Seed Inventories
            $invAyam = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Daging Ayam Fresh'],
                ['category' => 'bahan_makanan', 'stock' => 50, 'unit' => 'kg', 'min_stock' => 10, 'unit_price' => 38000, 'notes' => 'Pasokan harian']
            );
            $invBeras = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Beras Putih Premium'],
                ['category' => 'bahan_makanan', 'stock' => 100, 'unit' => 'kg', 'min_stock' => 20, 'unit_price' => 14000, 'notes' => 'Stok mingguan']
            );
            $invMadu = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Kecap Manis & Madu'],
                ['category' => 'bahan_makanan', 'stock' => 30, 'unit' => 'liter', 'min_stock' => 5, 'unit_price' => 28000, 'notes' => 'Bumbu olahan']
            );
            $invBumbu = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Bumbu Rempah & Bawang'],
                ['category' => 'bahan_makanan', 'stock' => 20, 'unit' => 'kg', 'min_stock' => 4, 'unit_price' => 35000, 'notes' => 'Racikan dapur']
            );
            $invTelur = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Telur Ayam Fresh'],
                ['category' => 'bahan_makanan', 'stock' => 200, 'unit' => 'butir', 'min_stock' => 30, 'unit_price' => 2000, 'notes' => 'Pasokan telur']
            );
            $invMinyak = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Minyak Goreng'],
                ['category' => 'bahan_makanan', 'stock' => 40, 'unit' => 'liter', 'min_stock' => 8, 'unit_price' => 16000, 'notes' => 'Minyak komersial']
            );
            $invMie = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Mie Basah & Seafood'],
                ['category' => 'bahan_makanan', 'stock' => 35, 'unit' => 'kg', 'min_stock' => 5, 'unit_price' => 32000, 'notes' => 'Bahan mie & seafood']
            );
            $invKentang = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Kentang Potong Beku'],
                ['category' => 'bahan_makanan', 'stock' => 40, 'unit' => 'kg', 'min_stock' => 8, 'unit_price' => 25000, 'notes' => 'Bahan french fries kentang']
            );
            $invPisang = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Pisang & Keju Parut'],
                ['category' => 'bahan_makanan', 'stock' => 35, 'unit' => 'kg', 'min_stock' => 6, 'unit_price' => 22000, 'notes' => 'Pisang kepok & keju cheddar']
            );
            $invTeh = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Daun Teh Hitam'],
                ['category' => 'bahan_minuman', 'stock' => 15, 'unit' => 'kg', 'min_stock' => 3, 'unit_price' => 50000, 'notes' => 'Teh racikan']
            );
            $invGula = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Gula Pasir Murni'],
                ['category' => 'bahan_minuman', 'stock' => 50, 'unit' => 'kg', 'min_stock' => 10, 'unit_price' => 17000, 'notes' => 'Pemanis minuman']
            );
            $invSirup = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Sirup Alpukat / Buah'],
                ['category' => 'bahan_minuman', 'stock' => 25, 'unit' => 'liter', 'min_stock' => 5, 'unit_price' => 45000, 'notes' => 'Bahan es & jus']
            );
            $invKopi = Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Bubuk Kopi Robusta'],
                ['category' => 'bahan_minuman', 'stock' => 20, 'unit' => 'kg', 'min_stock' => 4, 'unit_price' => 60000, 'notes' => 'Biji kopi pilihan roasted']
            );
            Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Wajan Goreng Stainless Steel'],
                ['category' => 'peralatan', 'stock' => 8, 'unit' => 'unit', 'min_stock' => 2, 'unit_price' => 250000, 'notes' => 'Peralatan dapur']
            );
            Inventory::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Blender Juicer Commercial'],
                ['category' => 'peralatan', 'stock' => 4, 'unit' => 'unit', 'min_stock' => 1, 'unit_price' => 850000, 'notes' => 'Peralatan bar minuman']
            );

            // Seed Multi-Ingredient Recipes (BOM) for All 9 Menus
            // 1. Nasi Goreng Spesial
            $menuNasgor = Menu::where('branch_id', $branch->id)->where('name', 'Nasi Goreng Spesial')->first();
            if ($menuNasgor) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuNasgor->id, 'inventory_id' => $invBeras->id], ['quantity' => 0.20]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuNasgor->id, 'inventory_id' => $invTelur->id], ['quantity' => 1.00]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuNasgor->id, 'inventory_id' => $invMinyak->id], ['quantity' => 0.05]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuNasgor->id, 'inventory_id' => $invBumbu->id], ['quantity' => 0.02]);
            }

            // 2. Mie Goreng Seafood
            $menuMieGoreng = Menu::where('branch_id', $branch->id)->where('name', 'Mie Goreng Seafood')->first();
            if ($menuMieGoreng) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuMieGoreng->id, 'inventory_id' => $invMie->id], ['quantity' => 0.20]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuMieGoreng->id, 'inventory_id' => $invTelur->id], ['quantity' => 1.00]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuMieGoreng->id, 'inventory_id' => $invMinyak->id], ['quantity' => 0.05]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuMieGoreng->id, 'inventory_id' => $invBumbu->id], ['quantity' => 0.02]);
            }

            // 3. Ayam Bakar Madu
            $menuAyamBakar = Menu::where('branch_id', $branch->id)->where('name', 'Ayam Bakar Madu')->first();
            if ($menuAyamBakar) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuAyamBakar->id, 'inventory_id' => $invAyam->id], ['quantity' => 0.25]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuAyamBakar->id, 'inventory_id' => $invMadu->id], ['quantity' => 0.05]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuAyamBakar->id, 'inventory_id' => $invBumbu->id], ['quantity' => 0.03]);
            }

            // 4. Sate Ayam
            $menuSate = Menu::where('branch_id', $branch->id)->where('name', 'Sate Ayam')->first();
            if ($menuSate) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuSate->id, 'inventory_id' => $invAyam->id], ['quantity' => 0.20]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuSate->id, 'inventory_id' => $invMadu->id], ['quantity' => 0.04]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuSate->id, 'inventory_id' => $invBumbu->id], ['quantity' => 0.02]);
            }

            // 5. French Fries
            $menuFries = Menu::where('branch_id', $branch->id)->where('name', 'French Fries')->first();
            if ($menuFries) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuFries->id, 'inventory_id' => $invKentang->id], ['quantity' => 0.15]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuFries->id, 'inventory_id' => $invMinyak->id], ['quantity' => 0.05]);
            }

            // 6. Pisang Goreng Keju
            $menuPisang = Menu::where('branch_id', $branch->id)->where('name', 'Pisang Goreng Keju')->first();
            if ($menuPisang) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuPisang->id, 'inventory_id' => $invPisang->id], ['quantity' => 0.20]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuPisang->id, 'inventory_id' => $invMinyak->id], ['quantity' => 0.05]);
            }

            // 7. Es Teh Manis
            $menuEsTeh = Menu::where('branch_id', $branch->id)->where('name', 'Es Teh Manis')->first();
            if ($menuEsTeh) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuEsTeh->id, 'inventory_id' => $invTeh->id], ['quantity' => 0.05]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuEsTeh->id, 'inventory_id' => $invGula->id], ['quantity' => 0.03]);
            }

            // 8. Jus Alpukat
            $menuJus = Menu::where('branch_id', $branch->id)->where('name', 'Jus Alpukat')->first();
            if ($menuJus) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuJus->id, 'inventory_id' => $invSirup->id], ['quantity' => 0.10]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuJus->id, 'inventory_id' => $invGula->id], ['quantity' => 0.03]);
            }

            // 9. Kopi Hitam Spesial
            $menuKopi = Menu::where('branch_id', $branch->id)->where('name', 'Kopi Hitam Spesial')->first();
            if ($menuKopi) {
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuKopi->id, 'inventory_id' => $invKopi->id], ['quantity' => 0.02]);
                \App\Models\MenuIngredient::firstOrCreate(['menu_id' => $menuKopi->id, 'inventory_id' => $invGula->id], ['quantity' => 0.02]);
            }

            // Seed Expenses
            Expense::firstOrCreate(
                ['branch_id' => $branch->id, 'title' => 'Pembelian Bahan Baku Makanan'],
                ['category' => 'pembelian_bahan', 'amount' => 1850000, 'date' => now()->subDays(2), 'notes' => 'Pembelian beras, ayam, dan bumbu']
            );
            Expense::firstOrCreate(
                ['branch_id' => $branch->id, 'title' => 'Pembelian Peralatan Masak Baru'],
                ['category' => 'peralatan', 'amount' => 1100000, 'date' => now()->subDays(5), 'notes' => 'Penggantian wajan dan pisau dapur']
            );
        }
    }
}
