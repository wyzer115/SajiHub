<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
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
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@sajihub.com',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $branchesData = [
            [
                'name' => 'SajiHUB Jakarta Selatan',
                'address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'phone' => '021-7891234',
                'city' => 'Jakarta',
            ],
            [
                'name' => 'SajiHUB Bandung',
                'address' => 'Jl. Braga No. 45, Bandung',
                'phone' => '022-4201234',
                'city' => 'Bandung',
            ],
            [
                'name' => 'SajiHUB Surabaya',
                'address' => 'Jl. Tunjungan No. 78, Surabaya',
                'phone' => '031-5311234',
                'city' => 'Surabaya',
            ],
        ];

        $firstBranch = null;

        foreach ($branchesData as $index => $data) {
            $branch = Branch::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
            ]);
            
            if (!$firstBranch) {
                $firstBranch = $branch;

                User::create([
                    'branch_id' => $branch->id,
                    'name' => 'Admin',
                    'email' => 'admin@sajihub.com',
                    'username' => 'admin',
                    'password' => Hash::make('password'),
                    'role' => 'admin_cabang',
                ]);

                User::create([
                    'branch_id' => $branch->id,
                    'name' => 'Kasir',
                    'email' => 'kasir@sajihub.com',
                    'username' => 'kasir',
                    'password' => Hash::make('password'),
                    'role' => 'kasir',
                ]);

                User::create([
                    'branch_id' => $branch->id,
                    'name' => 'Koki',
                    'email' => 'koki@sajihub.com',
                    'username' => 'koki',
                    'password' => Hash::make('password'),
                    'role' => 'koki',
                ]);
            }

            $catMakanan = Category::create(['branch_id' => $branch->id, 'name' => 'Makanan Utama']);
            $catMinuman = Category::create(['branch_id' => $branch->id, 'name' => 'Minuman']);
            $catAppetizer = Category::create(['branch_id' => $branch->id, 'name' => 'Appetizer']);
            $catDessert = Category::create(['branch_id' => $branch->id, 'name' => 'Dessert']);

            for ($i = 1; $i <= 4; $i++) {
                Table::create([
                    'branch_id' => $branch->id,
                    'table_number' => "Table {$i}",
                    'qr_code_token' => Str::random(32),
                    'status' => 'empty',
                ]);
            }

            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMakanan->id, 'name' => 'Nasi Goreng Spesial', 'price' => 35000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMakanan->id, 'name' => 'Mie Goreng Seafood', 'price' => 38000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMakanan->id, 'name' => 'Ayam Bakar Madu', 'price' => 42000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMakanan->id, 'name' => 'Sate Ayam', 'price' => 30000]);

            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMinuman->id, 'name' => 'Es Teh Manis', 'price' => 8000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMinuman->id, 'name' => 'Jus Alpukat', 'price' => 18000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMinuman->id, 'name' => 'Kopi Susu', 'price' => 15000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catMinuman->id, 'name' => 'Air Mineral', 'price' => 5000]);

            Menu::create(['branch_id' => $branch->id, 'category_id' => $catAppetizer->id, 'name' => 'Tahu Crispy', 'price' => 12000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catAppetizer->id, 'name' => 'Lumpia Goreng', 'price' => 15000]);

            Menu::create(['branch_id' => $branch->id, 'category_id' => $catDessert->id, 'name' => 'Es Campur', 'price' => 20000]);
            Menu::create(['branch_id' => $branch->id, 'category_id' => $catDessert->id, 'name' => 'Pudding Coklat', 'price' => 15000]);
        }

        if ($firstBranch) {
            $menus = Menu::where('branch_id', $firstBranch->id)->get();
            $tables = Table::where('branch_id', $firstBranch->id)->get();
            $kasir = User::where('branch_id', $firstBranch->id)->where('role', 'kasir')->first();

            $createOrder = function($status, $payment, $daysAgo = 0) use ($firstBranch, $menus, $tables, $kasir) {
                $order = Order::create([
                    'branch_id' => $firstBranch->id,
                    'table_id' => $tables->random()->id,
                    'user_id' => $kasir->id,
                    'customer_name' => 'Customer ' . Str::random(4),
                    'total_price' => 0,
                    'payment_status' => $payment,
                    'order_status' => $status,
                    'created_at' => Carbon::now()->subDays($daysAgo),
                    'updated_at' => Carbon::now()->subDays($daysAgo),
                ]);

                $total = 0;
                $numItems = rand(2, 4);
                
                for ($i = 0; $i < $numItems; $i++) {
                    $menu = $menus->random();
                    $qty = rand(1, 3);
                    $price = $menu->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);
                    
                    $total += ($qty * $price);
                }
                
                $order->update(['total_price' => $total]);
            };

            for ($i = 0; $i < 5; $i++) {
                $createOrder('completed', 'paid', 0);
            }

            for ($i = 0; $i < 3; $i++) {
                $createOrder('completed', 'paid', 1);
            }

            $createOrder('pending', 'unpaid', 0);
            $createOrder('cooking', 'unpaid', 0);
        }
    }
}
