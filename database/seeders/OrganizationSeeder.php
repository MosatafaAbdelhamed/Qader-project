<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            ['name' => 'الهلال الأحمر المصري', 'email' => 'erc@example.com', 'location' => 'القاهرة'],
            ['name' => 'بنك الطعام المصري', 'email' => 'foodbank@example.com', 'location' => 'الجيزة'],
            ['name' => 'مؤسسة مصر الخير', 'email' => 'misrelkheir@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة مجدي يعقوب للقلب', 'email' => 'magdi@example.com', 'location' => 'أسوان'],
            ['name' => 'جمعية رسالة للأعمال الخيرية', 'email' => 'resala@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة أهل مصر للتنمية', 'email' => 'ahlmisr@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة صناع الحياة مصر', 'email' => 'lifemakers@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية الأورمان', 'email' => 'orman@example.com', 'location' => 'الجيزة'],
            ['name' => 'مؤسسة بهية لعلاج سرطان الثدي', 'email' => 'baheya@example.com', 'location' => 'الجيزة'],
            ['name' => 'مستشفى 57357', 'email' => 'hospital57357@example.com', 'location' => 'القاهرة'],
            ['name' => 'مستشفى أبو الريش للأطفال', 'email' => 'aboreesh@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية كاريتاس مصر', 'email' => 'caritas@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية الحق في الحياة', 'email' => 'righttolife@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية زهور الحياة', 'email' => 'zohorelhaya@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة التعليم أولا', 'email' => 'educationfirst@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة المبرة الإسلامية', 'email' => 'mabra@example.com', 'location' => 'الإسكندرية'],
            ['name' => 'مؤسسة الأمل لرعاية ذوي الاحتياجات الخاصة', 'email' => 'hopefoundation@example.com', 'location' => 'المنوفية'],
            ['name' => 'جمعية أصدقاء المبادرة القومية ضد السرطان', 'email' => 'afnc@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة الكبد المصري', 'email' => 'liverfoundation@example.com', 'location' => 'المنصورة'],
            ['name' => 'جمعية البسمة للأيتام', 'email' => 'basma@example.com', 'location' => 'الفيوم'],
            ['name' => 'جمعية قلوب الرحمة', 'email' => 'hearts@example.com', 'location' => 'أسيوط'],
            ['name' => 'مؤسسة فودافون مصر لتنمية المجتمع', 'email' => 'vodafonefoundation@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة ساويرس للتنمية الاجتماعية', 'email' => 'sawirisfoundation@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية تنمية المرأة الريفية', 'email' => 'ruralwomen@example.com', 'location' => 'بني سويف'],
            ['name' => 'مؤسسة حماية الطفل', 'email' => 'childprotection@example.com', 'location' => 'سوهاج'],
            ['name' => 'جمعية النجدة الاجتماعية', 'email' => 'najda@example.com', 'location' => 'الإسماعيلية'],
            ['name' => 'مؤسسة قرى الأطفال SOS مصر', 'email' => 'sosvillage@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية النهضة للخدمات الإنسانية', 'email' => 'nahda@example.com', 'location' => 'المنوفية'],
            ['name' => 'مؤسسة النور والأمل لرعاية المكفوفين', 'email' => 'noorhope@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة رؤية لمساعدة ذوي الاحتياجات الخاصة', 'email' => 'roya@example.com', 'location' => 'الإسكندرية'],
            ['name' => 'جمعية الشباب والمستقبل', 'email' => 'youthfuture@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة المساعدات الإنسانية', 'email' => 'aidfoundation@example.com', 'location' => 'المنيا'],
            ['name' => 'جمعية التنمية المستدامة', 'email' => 'sustainabledev@example.com', 'location' => 'الأقصر'],
            ['name' => 'مؤسسة الأمل لرعاية الأيتام', 'email' => 'hopeorphans@example.com', 'location' => 'الشرقية'],
            ['name' => 'جمعية حياة كريمة', 'email' => 'hayakarima@example.com', 'location' => 'القاهرة'],
            ['name' => 'مؤسسة الفرحة للتنمية', 'email' => 'farhahdevelopment@example.com', 'location' => 'المنوفية'],
            ['name' => 'جمعية الأمل لرعاية المعاقين', 'email' => 'hopefordisabled@example.com', 'location' => 'الدقهلية'],
            ['name' => 'مؤسسة دعم المرأة المصرية', 'email' => 'womensupport@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية الطفولة السعيدة', 'email' => 'happychildhood@example.com', 'location' => 'أسيوط'],
            ['name' => 'مؤسسة تنمية الصعيد', 'email' => 'upperdevelopment@example.com', 'location' => 'قنا'],
            ['name' => 'جمعية النهضة العلمية', 'email' => 'scientificrise@example.com', 'location' => 'كفر الشيخ'],
            ['name' => 'مؤسسة إنقاذ الحياة', 'email' => 'lifesaving@example.com', 'location' => 'الغربية'],
            ['name' => 'مؤسسة دعم الفقراء', 'email' => 'poorsupport@example.com', 'location' => 'البحيرة'],
            ['name' => 'جمعية شباب النهضة', 'email' => 'youthrenaissance@example.com', 'location' => 'القليوبية'],
            ['name' => 'مؤسسة الأمل الجديد', 'email' => 'newhope@example.com', 'location' => 'القاهرة'],
            ['name' => 'جمعية التعليم للجميع', 'email' => 'educationforall@example.com', 'location' => 'القاهرة']
        ];

        foreach ($organizations as &$organization) {
            $organization['password'] = Hash::make('password123');
        }

        foreach ($organizations as $organization) {
            Organization::updateOrCreate(
                ['email' => $organization['email']],
                $organization
            );
        }
    }
}
