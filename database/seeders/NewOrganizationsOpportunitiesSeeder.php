<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use App\Models\Category;
use App\Models\Organization;
use Carbon\Carbon;

class NewOrganizationsOpportunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $organizations = Organization::where('email', 'like', 'org%@example.com')->get();

        $titles = [
            'برنامج تدريب مجاني', 'ورشة عمل متخصصة', 'حملة توعية', 'دورة تعليمية',
            'مشروع تنموي', 'فرصة تطوع في الخارج', 'حدث خيري', 'برنامج إرشاد مهني',
            'فرصة لتنظيف الحدائق', 'توزيع مساعدات إنسانية', 'بناء مساكن للأسر المحتاجة',
            'تنظيم مؤتمر علمي', 'برنامج دعم نفسي للأطفال', 'فرصة مساعدة كبار السن',
            'مشروع تطوير تقني', 'حملة لدعم ريادة الأعمال', 'تنظيم حدث رياضي',
            'فرصة لتقديم استشارات قانونية', 'تنظيم معرض ثقافي', 'برنامج تدريب إداري'
        ];

        foreach ($organizations as $organization) {
            for ($i = 0; $i < 20; $i++) {
                $category = $categories->random();
                Opportunity::create([
                    'title' => $titles[array_rand($titles)],
                    'description' => 'فرصة رائعة للمشاركة في ' . $category->name,
                    'start' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'end' => Carbon::now()->addDays(rand(31, 60))->format('Y-m-d'),
                    'category_id' => $category->category_id,
                    'organization_id' => $organization->organization_id,
                ]);
            }
        }
    }
}
