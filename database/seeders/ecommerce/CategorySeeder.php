<?php

namespace Database\Seeders\ecommerce;

use Illuminate\Database\Seeder;
use Domain\MarketPlace\Models\Catalog\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $categories = [
            [
                'title' => ['en' => 'Solar energy equipment', 'ar' => 'معدات الطاقة الشمسية '],
                'slug' => 'Solar',
                'description' => ['en' => 'Solar energy equipment', 'ar' => 'معدات الطاقة الشمسية '],
                'image' => 'upload/catalogs/categories/r8rFqQFcjsiXmX7B8ql38rPGCppodSVsh4EdbQaw.webp',
            ],
            [
                'title' => ['en' => 'Wind power equipment', 'ar' => 'معدات طاقة الرياح '],
                'slug' => 'Wind',
                'description' => ['en' => 'Wind power equipment', 'ar' => 'معدات طاقة الرياح '],
                'image' => 'upload/catalogs/categories/t5EmIu8HApfF5095A9MMk711VQMcLIfuJXenZkKr.webp',
            ],
            [
                'title' => ['en' => 'Environment', 'ar' => 'البيئة '],
                'slug' => 'Environment',
                'description' => ['en' => 'Environment', 'ar' => 'البيئة'],
                'image' => 'upload/catalogs/categories/tUlVc5zdBDzi9RtWCNe7hm2dUiN0Td8eNsVgvXf0.webp',
            ],
            [
                'title' => ['en' => 'Safety equipment', 'ar' => 'معدات سلامة '],
                'slug' => 'Safety',
                'description' => ['en' => 'Safety equipment', 'ar' => 'معدات سلامة '],
                'image' => 'upload/catalogs/categories/XniTJFjJSL35KhwRzgbASb8C5RtXh0HrdMs1sUyX.webp',
            ],
            [
                'title' => ['en' => 'wells', 'ar' => 'مياه وابار '],
                'slug' => 'wells',
                'description' => ['en' => 'wells', 'ar' => 'ابار '],
                'image' => 'upload/catalogs/categories/PBc9yk8BiExQ88PvPkU4GuuTYVO7P3cWYDgsH63g.webp',
            ],
            [
                'title' => ['en' => 'electricity', 'ar' => 'كهرباء'],
                'slug' => 'electricity',
                'description' => ['en' => 'electricity', 'ar' => 'كهرباء'],
                'image' => 'upload/catalogs/categories/DE3VSRGmKtk6K4LAfrFHcgNr40TlFPBdEMuimK1U.webp',
            ],
            [
                'title' => ['en' => 'building materials', 'ar' => 'مواد بناء'],
                'slug' => 'building',
                'description' => ['en' => 'building materials', 'ar' => 'مواد بناء'],
                'image' => 'upload/catalogs/categories/BJWzgclI1AKkDzJxmAyS5Ev4Ga6ZgxdNkmZvzZUA.webp',
            ],
            [
                'title' => ['en' => 'Batteries', 'ar' => 'بطاريات'],
                'slug' => 'Batteries',
                'description' => ['en' => 'Batteries', 'ar' => 'بطاريات'],
                'image' => 'upload/catalogs/categories/2lz8j92UYTn2BhLz5MXsYXyiZ77fUSzTUgSetpOV.webp',
            ],
            [
                'title' => ['en' => 'Cables', 'ar' => 'كابلات'],
                'slug' => 'Cables',
                'description' => ['en' => 'Cables', 'ar' => 'كابلات'],
                'image' => 'upload/catalogs/categories/ovUnoXlTP0rLBK3MYr97QdhGLALC1bIRaS0xfkxi.webp',
            ],
            [
                'title' => ['en' => 'Converters', 'ar' => 'محولات'],
                'slug' => 'Converters',
                'description' => ['en' => 'Converters', 'ar' => 'محولات'],
                'image' => 'upload/catalogs/categories/qOLBroOXSp0eHETxgqUUhkr4qpUH95o6rdf8A9Hd.webp',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}

