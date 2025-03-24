<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use App\Models\Category;
use App\Models\Organization;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'تدريبى', 'بيئى', 'قانونى', 'مالي', 'فني',
            'تسويقى', 'تقنى', 'صحى', 'ترفيهى',
            'عام', 'الامن والسلامة', 'التربية والتعليم',
            'نفسى', 'اجتماعى', 'ثقافى', 'خدمى', 'رياضى', 'هندسى',
            'ادارى', 'دينى', 'اسكان', 'ضيوف الرحمن', 'تنظيمى', 'اغاثى'
        ];

        $titles = [
            'برنامج تدريب مجاني', 'ورشة عمل متخصصة', 'حملة توعية', 'دورة تعليمية',
            'مشروع تنموي', 'فرصة تطوع في الخارج', 'حدث خيري', 'برنامج إرشاد مهني',
            'فرصة لتنظيف الحدائق', 'توزيع مساعدات إنسانية', 'بناء مساكن للأسر المحتاجة',
            'تنظيم مؤتمر علمي', 'برنامج دعم نفسي للأطفال', 'فرصة مساعدة كبار السن',
            'مشروع تطوير تقني', 'حملة لدعم ريادة الأعمال', 'تنظيم حدث رياضي',
            'فرصة لتقديم استشارات قانونية', 'تنظيم معرض ثقافي', 'برنامج تدريب إداري'
        ];


        $organizations = Organization::pluck('organization_id')->toArray();

        foreach ($categories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            for ($i = 0; $i < 25; $i++) {
                Opportunity::create([
                    'title' => $titles[array_rand($titles)],
                    'description' => 'فرصة رائعة للمشاركة في ' . $category->name,
                    'start' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'end' => now()->addDays(rand(31, 60))->format('Y-m-d'),
                    'category_id' => $category->category_id,
                    'organization_id' => $organizations[array_rand($organizations)],
                ]);
            }
        }
    }
}
