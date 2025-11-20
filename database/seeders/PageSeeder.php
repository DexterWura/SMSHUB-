<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (Page::where('slug', 'order')->count() == 0) {
            $page = new Page;
            $page->title = 'Rent a Number';
            $page->content = 'Rent a Shared or Private Number at a incredible cost';
            $page->slug = 'order';
            $page->meta = null;
            $page->header = null;
            $page->save();
        }
    }
}
