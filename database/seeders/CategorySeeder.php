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
            'تدريبى', 'بيئى', 'قانونى', 'مالي', 'فني',
            'تسويقى', 'تقنى', 'صحى', 'ترفيهى',
            'عام', 'الامن والسلامة', 'التربية والتعليم',
            'نفسى', 'اجتماعى', 'ثقافى', 'خدمى', 'رياضى',
            'هندسى', 'ادارى', 'دينى', 'اسكان', 'ضيوف الرحمن', 'تنظيمى', 'اغاثى'
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => trim($category)]);
        }



    }
}
