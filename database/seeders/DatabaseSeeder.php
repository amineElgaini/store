<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderPackageItem;
use App\Models\OrderPackageVariantItem;
use App\Models\OrderProductItem;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'is_admin'  => true,
            'address' => '123 Admin Street',
            'phone' => '123-456-7890',
        ]);
        
        User::create([
            'name' => 'client',
            'username' => 'client',
            'password' => Hash::make('client'),
            'is_admin'  => false,
            'address' => '456 User Road',
            'phone' => '987-654-3210',
        ]);        

        // Categories
        Category::insert([
            ['name' => 'T-Shirts'],
            ['name' => 'Jeans'],
            ['name' => 'Jackets'],
            ['name' => 'Accessories'],
        ]);

        // Sizes
        Size::insert([
            ['name' => 'S'],
            ['name' => 'M'],
            ['name' => 'L'],
            ['name' => 'XL'],
            ['name' => 'XXL'],
            ['name' => 'XXXL'],
        ]);

        // Colors
        Color::insert([
            ['name' => 'White / Blanc', 'code' => '#FFFFFF'],
            ['name' => 'Black / Noir', 'code' => '#000000'],
            ['name' => 'Blue / Bleu', 'code' => '#0000FF'],
            ['name' => 'Red / Rouge', 'code' => '#FF0000'],
            ['name' => 'Green / Vert', 'code' => '#008000'],
            ['name' => 'Yellow / Jaune', 'code' => '#FFFF00'],
            ['name' => 'Orange / Orange', 'code' => '#FFA500'],
            ['name' => 'Purple / Violet', 'code' => '#800080'],
            ['name' => 'Pink / Rose', 'code' => '#FFC0CB'],
            ['name' => 'Gray / Gris', 'code' => '#808080'],
        ]);
        

        // Products:
        $tShirtsId = Category::where('name', 'T-Shirts')->first()->id;
        $jeansId   = Category::where('name', 'Jeans')->first()->id;
        $jacketsId = Category::where('name', 'Jackets')->first()->id;
        $accessoriesId = Category::where('name', 'Accessories')->first()->id;

        Product::insert([
            ['name' => 'Basic White T-Shirt', 'description' => 'Plain white cotton t-shirt.', 'price' => 9.99, 'category_id' => $tShirtsId],
            ['name' => 'Graphic Tee', 'description' => 'Cool printed design t-shirt.', 'price' => 14.99, 'category_id' => $tShirtsId],
            ['name' => 'Blue Denim Jeans', 'description' => 'Classic blue jeans.', 'price' => 29.99, 'category_id' => $jeansId],
            ['name' => 'Black Skinny Jeans', 'description' => 'Slim fit black jeans.', 'price' => 34.99, 'category_id' => $jeansId],
            ['name' => 'Leather Jacket', 'description' => 'Stylish leather outerwear.', 'price' => 89.99, 'category_id' => $jacketsId],
            ['name' => 'Windbreaker Jacket', 'description' => 'Lightweight windbreaker for daily wear.', 'price' => 49.99, 'category_id' => $jacketsId],
            ['name' => 'Beanie Hat', 'description' => 'Warm winter beanie.', 'price' => 12.99, 'category_id' => $accessoriesId],
            ['name' => 'Leather Belt', 'description' => 'Durable black belt.', 'price' => 19.99, 'category_id' => $accessoriesId],
            ['name' => 'Sunglasses', 'description' => 'Stylish UV protection.', 'price' => 24.99, 'category_id' => $accessoriesId],
            ['name' => 'Crew Neck T-Shirt', 'description' => 'Basic crew neck style.', 'price' => 11.99, 'category_id' => $tShirtsId],
        ]);

        // Add product variants
        $sizes = Size::all();
        $colors = Color::all();

        Product::all()->each(function ($product) use ($sizes, $colors) {
            $combinations = [];
            for ($i = 0; $i < 3; $i++) {
                do {
                    $size = $sizes->random();
                    $color = $colors->random();
                    $key = $size->id . '-' . $color->id;
                } while (in_array($key, $combinations));
                $combinations[] = $key;

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'stock' => 10,
                ]);
            }
        });

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

        $admin = User::where('is_admin', true)->first();
        $user = User::where('is_admin', false)->first();

        // Order 1: product-based
        $order1 = Order::create([
            'user_id' => $user->id,
            'total_price' => 0,
            'status' => 'pending',
        ]);

        $variants = ProductVariant::inRandomOrder()->take(2)->get();
        $total = 0;

        foreach ($variants as $variant) {
            $quantity = rand(1, 3);
            OrderProductItem::create([
                'order_id' => $order1->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
            ]);
            $total += $variant->product->price * $quantity;
        }

        $order1->update(['total_price' => $total]);

        // Order 2: package-based
        $order2 = Order::create([
            'user_id' => $admin->id,
            'total_price' => 0,
            'status' => 'pending',
        ]);

        $package = Package::inRandomOrder()->first();
        $quantity = rand(1, 2);

        $orderPackageItem = OrderPackageItem::create([
            'order_id' => $order2->id,
            'package_id' => $package->id,
            'quantity' => $quantity,
        ]);

        $order2->update(['total_price' => $package->price * $quantity]);

        $packageDetails = PackageDetail::where('package_id', $package->id)->get();

        foreach ($packageDetails as $detail) {
            $variantsForProduct = ProductVariant::where('product_id', $detail->product_id)->get();

            if ($variantsForProduct->isNotEmpty()) {
                $variant = $variantsForProduct->random();

                OrderPackageVariantItem::create([
                    'order_package_item_id' => $orderPackageItem->id,
                    'product_variant_id' => $variant->id,
                ]);
            }
        }
    }
}
