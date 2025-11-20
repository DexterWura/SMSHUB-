<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (Feature::count() == 0) {
            DB::table('features')->insert(['icon' => 'fas fa-user-secret', 'title' => 'Privacy', 'description' => 'Your privacy is very important! With our temporary phone numbers you don\'t have to provide your real phone number on other websites.', 'created_at' => '2019-01-01 00:00:00', 'updated_at' => '2019-01-01 00:00:00']);
            DB::table('features')->insert(['icon' => 'fas fa-dollar-sign', 'title' => 'Free SMS', 'description' => 'Our SMS receiver <strong>service is free</strong> for everyone and will always be free. We won\'t charge you for any SMS you receive or phone number you use.', 'created_at' => '2019-01-01 00:00:00', 'updated_at' => '2019-01-01 00:00:00']);
            DB::table('features')->insert(['icon' => 'fas fa-shipping-fast', 'title' => 'Fast Receiver', 'description' => 'The most text messages that were send to our sms numbers <strong>arrive after some seconds.</strong> This means you don\'t have to wait minutes or hours like on other sms platforms.', 'created_at' => '2019-01-01 00:00:00', 'updated_at' => '2019-01-01 00:00:00']);
        }
    }
}
