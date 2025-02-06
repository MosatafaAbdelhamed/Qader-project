<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            'تعليمي', 'طبي', 'اجتماعي', 'بيئي', 'ثقافي وفني',
            'رياضي', 'إعلامي وصحفي', 'تقني وبرمجي', 'قانوني',
            'مالي ومحاسبي', 'إغاثي وإنساني', 'أمني وسلامة',
            'ترفيهي', 'ريادة أعمال'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }


    }
}
