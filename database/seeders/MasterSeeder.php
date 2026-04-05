<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\ProductImage;
use App\Models\Coupon;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@mail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */
        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion'
        ]);

        $hijab = Category::create([
            'parent_id' => $fashion->id,
            'name' => 'Hijab',
            'slug' => 'hijab'
        ]);

        /*
        |--------------------------------------------------------------------------
        | BRANDS
        |--------------------------------------------------------------------------
        */
        $brand = Brand::create([
            'name' => 'Elzatta',
            'slug' => 'elzatta'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PRODUCT TANPA VARIANT
        |--------------------------------------------------------------------------
        */
        $product1 = Product::create([
            'category_id' => $hijab->id,
            'brand_id' => $brand->id,
            'name' => 'Hijab Basic Cream',
            'slug' => 'hijab-basic-cream',
            'price' => 35000,
            'stock' => 100,
            'has_variant' => false
        ]);

        ProductImage::create([
            'product_id' => $product1->id,
            'image_url' => 'products/hijab1.jpg',
            'is_primary' => true
        ]);

        /*
        |--------------------------------------------------------------------------
        | PRODUCT DENGAN VARIANT
        |--------------------------------------------------------------------------
        */
        $product2 = Product::create([
            'category_id' => $hijab->id,
            'brand_id' => $brand->id,
            'name' => 'Hijab Voal Premium',
            'slug' => 'hijab-voal-premium',
            'price' => 0,
            'stock' => 0,
            'has_variant' => true
        ]);

        // Variant 1
        $v1 = ProductVariant::create([
            'product_id' => $product2->id,
            'name' => 'Cream - L',
            'price' => 50000,
            'stock' => 50,
        ]);

        VariantAttribute::insert([
            [
                'variant_id' => $v1->id,
                'attribute_name' => 'Warna',
                'attribute_value' => 'Cream'
            ],
            [
                'variant_id' => $v1->id,
                'attribute_name' => 'Ukuran',
                'attribute_value' => 'L'
            ]
        ]);

        // Variant 2
        $v2 = ProductVariant::create([
            'product_id' => $product2->id,
            'name' => 'Hitam - XL',
            'price' => 55000,
            'stock' => 30,
        ]);

        VariantAttribute::insert([
            [
                'variant_id' => $v2->id,
                'attribute_name' => 'Warna',
                'attribute_value' => 'Hitam'
            ],
            [
                'variant_id' => $v2->id,
                'attribute_name' => 'Ukuran',
                'attribute_value' => 'XL'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | COUPONS
        |--------------------------------------------------------------------------
        */
        Coupon::create([
            'code' => 'DISKON10',
            'name' => 'Diskon 10%',
            'type' => 'percent',
            'value' => 10,
            'min_purchase' => 50000,
            'max_discount' => 20000,
            'quota' => 100,
        ]);

        Coupon::create([
            'code' => 'HEMAT5000',
            'name' => 'Potongan 5000',
            'type' => 'fixed',
            'value' => 5000,
        ]);
    }
}
