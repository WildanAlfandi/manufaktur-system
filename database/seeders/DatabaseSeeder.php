<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Supplier::truncate();
        Customer::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@manufaktur.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@manufaktur.com',
            'password' => bcrypt('password'),
            'role' => 'staff'
        ]);

        // Create categories
        $categories = [
            ['name' => 'Raw Materials', 'description' => 'Bahan baku produksi'],
            ['name' => 'Finished Goods', 'description' => 'Barang jadi'],
            ['name' => 'Spare Parts', 'description' => 'Suku cadang mesin'],
            ['name' => 'Packaging', 'description' => 'Bahan kemasan']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create suppliers
        $suppliers = [
            [
                'name' => 'PT. Supplier Utama',
                'contact_person' => 'Budi Santoso',
                'phone' => '021-1234567',
                'email' => 'supplier1@example.com',
                'address' => 'Jl. Industri No. 1, Jakarta'
            ],
            [
                'name' => 'CV. Material Prima',
                'contact_person' => 'Siti Aminah',
                'phone' => '021-7654321',
                'email' => 'supplier2@example.com',
                'address' => 'Jl. Perdagangan No. 10, Tangerang'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create customers
        $customers = [
            [
                'name' => 'PT. Customer Sejahtera',
                'contact_person' => 'Ahmad Fauzi',
                'phone' => '021-9876543',
                'email' => 'customer1@example.com',
                'address' => 'Jl. Bisnis No. 5, Bekasi'
            ],
            [
                'name' => 'CV. Mitra Jaya',
                'contact_person' => 'Dewi Lestari',
                'phone' => '021-5555555',
                'email' => 'customer2@example.com',
                'address' => 'Jl. Perdagangan No. 20, Jakarta Selatan'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create sample products
        $products = [
            [
                'code' => 'PRD-001',
                'name' => 'Baja Ringan 0.75mm',
                'category_id' => 1,
                'price' => 75000,
                'stock' => 100,
                'min_stock' => 20,
                'unit' => 'batang',
                'description' => 'Baja ringan kualitas premium'
            ],
            [
                'code' => 'PRD-002',
                'name' => 'Cat Tembok 5kg',
                'category_id' => 1,
                'price' => 125000,
                'stock' => 50,
                'min_stock' => 10,
                'unit' => 'kaleng',
                'description' => 'Cat tembok putih'
            ],
            [
                'code' => 'PRD-003',
                'name' => 'Kursi Kantor Ergonomis',
                'category_id' => 2,
                'price' => 850000,
                'stock' => 15,
                'min_stock' => 5,
                'unit' => 'unit',
                'description' => 'Kursi kantor dengan sandaran ergonomis'
            ],
            [
                'code' => 'PRD-004',
                'name' => 'Bearing 6205',
                'category_id' => 3,
                'price' => 45000,
                'stock' => 200,
                'min_stock' => 50,
                'unit' => 'pcs',
                'description' => 'Bearing untuk mesin produksi'
            ],
            [
                'code' => 'PRD-005',
                'name' => 'Kardus Packing 30x30x30',
                'category_id' => 4,
                'price' => 5000,
                'stock' => 500,
                'min_stock' => 100,
                'unit' => 'pcs',
                'description' => 'Kardus untuk packing produk'
            ]
        ];

        foreach ($products as $product) {
            $prod = Product::create($product);
            // Generate barcode
            $prod->barcode = 'BC' . str_pad($prod->id, 8, '0', STR_PAD_LEFT);
            $prod->save();
        }

        $this->command->info('Seeding completed successfully!');
        $this->command->info('Admin login: admin@manufaktur.com / password');
        $this->command->info('Staff login: staff@manufaktur.com / password');
    }
}
