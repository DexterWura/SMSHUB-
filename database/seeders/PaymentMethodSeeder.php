<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $methods = [
            [
                'name' => 'stripe',
                'is_active' => false
            ],
            [
                'name' => 'razorpay',
                'is_active' => false
            ],
            [
                'name' => 'paypal',
                'is_active' => false
            ],
            [
                'name' => 'coinpayments',
                'is_active' => false
            ],
            [
                'name' => 'paystack',
                'is_active' => false
            ],
            [
                'name' => 'paynow',
                'is_active' => false
            ],
        ];
        foreach ($methods as $method) {
            if (!PaymentMethod::where('name', $method['name'])->exists()) {
                PaymentMethod::create([
                    'name' => $method['name'],
                    'is_active' => $method['is_active'],
                ]);
            }
        }
    }
}
