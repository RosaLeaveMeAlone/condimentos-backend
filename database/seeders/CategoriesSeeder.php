<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Condimentos'],
            ['name' => 'Reposteria'],
            ['name' => 'Frutos Secos'],
            ['name' => 'Granos y cereales'],
            ['name' => 'Víveres'],
            ['name' => 'Químicos y más'],
        ];

        Category::insert($data);
    }
}
