<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds for Sinovation Promotions categories.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lanyards',
                'slug' => 'lanyards',
                'sort_order' => 1,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'ID',
                'slug' => 'id',
                'sort_order' => 2,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Headwear',
                'slug' => 'headwear',
                'sort_order' => 3,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'sort_order' => 4,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Fans',
                'slug' => 'fans',
                'sort_order' => 5,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Bags',
                'slug' => 'bags',
                'sort_order' => 6,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Drinkware',
                'slug' => 'drinkware',
                'sort_order' => 7,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Leisure',
                'slug' => 'leisure',
                'sort_order' => 8,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Office',
                'slug' => 'office',
                'sort_order' => 9,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pets',
                'slug' => 'pets',
                'sort_order' => 10,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Tools',
                'slug' => 'tools',
                'sort_order' => 11,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'sort_order' => 12,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Umbrellas',
                'slug' => 'umbrellas',
                'sort_order' => 13,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Socks',
                'slug' => 'socks',
                'sort_order' => 14,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'POS',
                'slug' => 'pos',
                'sort_order' => 15,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Eco',
                'slug' => 'eco',
                'sort_order' => 16,
                'icon_path' => 'https://sinovationpromotions.com/api/media/product-category/q8ifz2rx.png',
                'is_active' => true,
            ],
            [
                'name' => 'New',
                'slug' => 'new',
                'sort_order' => 17,
                'icon_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Hot',
                'slug' => 'hot',
                'sort_order' => 18,
                'icon_path' => null,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
