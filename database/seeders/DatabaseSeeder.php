<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\order;
use App\Models\OrderPackageItem;
use App\Models\OrderProductItem;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'isAdmin'  => true,
        ]);

        // Non-admin user
        User::create([
            'name' => 'user',
            'username' => 'user',
            'password' => Hash::make('user'),
            'isAdmin'  => false,
        ]);

        // Categories
        Category::insert([
            ['name' => 'T-Shirts'],
            ['name' => 'Jeans'],
            ['name' => 'Jackets'],
            ['name' => 'Accessories'],
        ]);

        // Products:
        $tShirtsId = Category::where('name', 'T-Shirts')->first()->id;
        $jeansId   = Category::where('name', 'Jeans')->first()->id;
        $jacketsId = Category::where('name', 'Jackets')->first()->id;
        $accessoriesId = Category::where('name', 'Accessories')->first()->id;

        Product::insert([
            ['name' => 'Basic White T-Shirt', 'description' => 'Plain white cotton t-shirt.', 'price' => 9.99, 'stock' => 50/* , 'image' => 'white_tshirt.jpg' */, 'category_id' => $tShirtsId],
            ['name' => 'Graphic Tee', 'description' => 'Cool printed design t-shirt.', 'price' => 14.99, 'stock' => 40,/*  'image' => 'graphic_tee.jpg', */ 'category_id' => $tShirtsId],
            ['name' => 'Blue Denim Jeans', 'description' => 'Classic blue jeans.', 'price' => 29.99, 'stock' => 30,/*  'image' => 'blue_jeans.jpg', */ 'category_id' => $jeansId],
            ['name' => 'Black Skinny Jeans', 'description' => 'Slim fit black jeans.', 'price' => 34.99, 'stock' => 25,/*  'image' => 'black_jeans.jpg', */ 'category_id' => $jeansId],
            ['name' => 'Leather Jacket', 'description' => 'Stylish leather outerwear.', 'price' => 89.99, 'stock' => 15,/*  'image' => 'leather_jacket.jpg', */ 'category_id' => $jacketsId],
            ['name' => 'Windbreaker Jacket', 'description' => 'Lightweight windbreaker for daily wear.', 'price' => 49.99, 'stock' => 20,/*  'image' => 'windbreaker.jpg', */ 'category_id' => $jacketsId],
            ['name' => 'Beanie Hat', 'description' => 'Warm winter beanie.', 'price' => 12.99, 'stock' => 60,/*  'image' => 'beanie.jpg', */ 'category_id' => $accessoriesId],
            ['name' => 'Leather Belt', 'description' => 'Durable black belt.', 'price' => 19.99, 'stock' => 35,/*  'image' => 'belt.jpg', */ 'category_id' => $accessoriesId],
            ['name' => 'Sunglasses', 'description' => 'Stylish UV protection.', 'price' => 24.99, 'stock' => 45,/*  'image' => 'sunglasses.jpg', */ 'category_id' => $accessoriesId],
            ['name' => 'Crew Neck T-Shirt', 'description' => 'Basic crew neck style.', 'price' => 11.99, 'stock' => 38,/*  'image' => 'crew_neck.jpg', */ 'category_id' => $tShirtsId],
        ]);

        // Create 2 packages
        $package1 = Package::create([
            'name' => 'Summer Essentials',
            'price' => 59.99,
        ]);

        $package2 = Package::create([
            'name' => 'Winter Warmers',
            'price' => 89.99,
        ]);

        // Get 6 products to assign (3 for each)
        $products = Product::inRandomOrder()->take(6)->get();

        // Add products to packages
        foreach ($products->take(3) as $product) {
            PackageDetail::create([
                'package_id' => $package1->id,
                'product_id' => $product->id,
            ]);
        }

        foreach ($products->slice(3, 3) as $product) {
            PackageDetail::create([
                'package_id' => $package2->id,
                'product_id' => $product->id,
            ]);
        }
        
        // Get users
        $admin = User::where('isAdmin', true)->first();
        $user = User::where('isAdmin', false)->first();

        // Create Order 1: Product-based order
        $order1 = Order::create([
            'user_id' => $user->id,
            'total_price' => 0, // will calculate below
            'status' => 'pending',
        ]);

        $products = Product::inRandomOrder()->take(2)->get();
        $total = 0;

        foreach ($products as $product) {
            $quantity = rand(1, 3);
            OrderProductItem::create([
                'order_id' => $order1->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $total += $product->price * $quantity;
        }

        $order1->update(['total_price' => $total]);

        // Create Order 2: Package-based order
        $order2 = order::create([
            'user_id' => $admin->id,
            'total_price' => 0,
            'status' => 'pending',
        ]);

        $package = Package::inRandomOrder()->first();
        $quantity = rand(1, 2);

        OrderPackageItem::create([
            'order_id' => $order2->id,
            'package_id' => $package->id,
            'quantity' => $quantity,
        ]);

        $order2->update(['total_price' => $package->price * $quantity]);
    }
}
