<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Opportunity;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $opportunities = [
            [
                'title' => 'فرصة تطوع في مجال التعليم',
                'description' => 'فرصة للتطوع في مشروع تعليمي للأطفال في المدارس.',
                'category_id' => 1, // تأكد من أن الفئة موجودة في قاعدة البيانات
                'user_id' => 1, // تأكد من وجود مستخدم بهذا الـ ID
            ],
            [
                'title' => 'فرصة تطوع في المجال الطبي',
                'description' => 'فرصة للمتطوعين في مجال تقديم الرعاية الصحية.',
                'category_id' => 2,
                'user_id' => 1,
            ],
            // أضف المزيد من الفرص هنا
        ];

        foreach ($opportunities as $opportunity) {
            Opportunity::create($opportunity);
        }

    }
}
