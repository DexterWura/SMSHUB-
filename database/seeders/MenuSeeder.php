<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $menus = [
            [
                'name' => 'Home',
                'link' => '/',
                'order' => 1
            ],
            [
                'name' => 'Order',
                'link' => '/order',
                'order' => 2
            ],
        ];
        foreach ($menus as $menu) {
            if (!Menu::where('name', $menu['name'])->exists()) {
                Menu::create([
                    'name' => $menu['name'],
                    'link' => $menu['link'],
                    'order' => $menu['order']
                ]);
            }
        }
    }
}
