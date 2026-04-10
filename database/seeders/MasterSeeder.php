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
