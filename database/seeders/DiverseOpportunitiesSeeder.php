<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Category;
use Carbon\Carbon;

class DiverseOpportunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود منظمة واحدة على الأقل
        $organization = Organization::first();
        if (!$organization) {
            $organization = Organization::create([
                'name' => 'جمعية التطوع النموذجية',
                'email' => 'org@example.com',
                'password' => bcrypt('password123'),
                'location' => 'القاهرة',
                'phone' => '01000000000',
            ]);
        }

        // التأكد من وجود فئة واحدة على الأقل
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'خدمة المجتمع',
            ]);
        }

        $titles = [
            // ثقافية
            'تنظيم مهرجان للقراءة في الحديقة العامة',
            'إدارة معرض فنون تشكيلية',
            'تنظيم ورشة كتابة قصص قصيرة',
            'تنظيم يوم ثقافي في مركز الشباب',
            'تنظيم مسابقة شعرية',
            // بيئية
            'مبادرة تنظيف شاطئ النيل',
            'حملة توعية عن إعادة التدوير',
            'زراعة أشجار في الأحياء السكنية',
            'تنظيم حملة تنظيف الشوارع',
            'مبادرة توعية عن الحفاظ على المياه',
            // رياضية
            'تنظيم ماراثون خيري',
            'تنظيم يوم رياضي في المدرسة',
            'إدارة بطولة كرة قدم للأيتام',
            'تنظيم يوم للرياضة النسائية',
            'تنظيم سباق دراجات للأطفال',
            // صحية
            'حملة توعية عن الصحة النفسية',
            'تنظيم حملة تبرع بالدم',
            'مساعدة في مركز علاج طبيعي',
            'تنظيم يوم صحي في الحي',
            'مبادرة توعية عن التغذية الصحية',
            // تعليمية
            'ورشة رسم للأطفال',
            'تنظيم دورة برمجة للمبتدئين',
            'مساعدة في تعليم الكبار القراءة والكتابة',
            'تنظيم ورشة أشغال يدوية',
            'تنظيم ورشة تصوير فوتوغرافي',
            // ترفيهية
            'تنظيم رحلة ترفيهية للأيتام',
            'تنظيم يوم ألعاب شعبية',
            'تنظيم يوم مفتوح في المكتبة العامة',
            'تنظيم يوم ترفيهي في دار المسنين',
            'تنظيم يوم سينمائي للأطفال',
            // اجتماعية
            'توزيع وجبات ساخنة على المحتاجين',
            'مساعدة كبار السن في التسوق',
            'مساعدة في توزيع ملابس شتوية',
            'مساعدة في مركز رعاية ذوي الاحتياجات الخاصة',
            'مساعدة في مركز رعاية المسنين',
            // تقنية
            'تنظيم ورشة تطوير مواقع إلكترونية',
            'تنظيم دورة أساسيات الأمن السيبراني',
            'تنظيم ورشة تصميم جرافيك',
            'تنظيم يوم تعليمي عن الذكاء الاصطناعي',
            'تنظيم مسابقة روبوتات للأطفال',
            // أخرى متنوعة
            'تنظيم حملة تبرع بالكتب',
            'مبادرة توعية عن الإسعافات الأولية',
            'تنظيم يوم للأنشطة الكشفية',
            'مساعدة في مركز تدريب مهني',
            'تنظيم يوم للقراءة الجماعية',
            'تنظيم حملة تبرع بالملابس',
            'تنظيم يوم ثقافي في الحي',
            'مبادرة توعية مرورية للأطفال',
            'تنظيم ورشة كتابة إبداعية',
            'مساعدة في مركز دعم نفسي',
            'تنظيم يوم للبرمجة التفاعلية',
            'تنظيم يوم للابتكار العلمي',
            'تنظيم يوم للسلامة المنزلية',
            'تنظيم يوم للأنشطة الكشفية',
            'تنظيم يوم للقراءة الجماعية',
        ];
        $descriptions = [
            'فرصة للمساهمة في خدمة المجتمع.',
            'شارك في عمل تطوعي مؤثر.',
            'فرصة لاكتساب خبرة جديدة ومساعدة الآخرين.',
            'انضم لفريقنا التطوعي واصنع فرقاً.',
            'فرصة رائعة للتواصل وخدمة المجتمع.',
        ];

        $today = Carbon::today();
        // فرص قصيرة (1-3 أيام)
        $shortDurations = [1, 2, 3];
        for ($i = 0; $i < 20; $i++) {
            $days = $shortDurations[array_rand($shortDurations)];
            $start = $today->copy()->addDays(rand(0, 30));
            $end = $start->copy()->addDays($days - 1);
            Opportunity::create([
                'title' => $titles[array_rand($titles)],
                'description' => $descriptions[array_rand($descriptions)],
                'category_id' => $category->category_id,
                'organization_id' => $organization->organization_id,
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'hours' => $days * 8,
            ]);
        }
        // فرص متوسطة (4-7 أيام)
        $mediumDurations = [4, 5, 6, 7];
        for ($i = 0; $i < 15; $i++) {
            $days = $mediumDurations[array_rand($mediumDurations)];
            $start = $today->copy()->addDays(rand(0, 30));
            $end = $start->copy()->addDays($days - 1);
            Opportunity::create([
                'title' => $titles[array_rand($titles)],
                'description' => $descriptions[array_rand($descriptions)],
                'category_id' => $category->category_id,
                'organization_id' => $organization->organization_id,
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'hours' => $days * 8,
            ]);
        }
        // فرص طويلة (8-20 يوم)
        for ($i = 0; $i < 15; $i++) {
            $days = rand(8, 20);
            $start = $today->copy()->addDays(rand(0, 30));
            $end = $start->copy()->addDays($days - 1);
            Opportunity::create([
                'title' => $titles[array_rand($titles)],
                'description' => $descriptions[array_rand($descriptions)],
                'category_id' => $category->category_id,
                'organization_id' => $organization->organization_id,
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'hours' => $days * 8,
            ]);
        }
        $this->command->info('تم إضافة فرص تطوع متنوعة المجالات والأنشطة بنجاح!');
    }
}
