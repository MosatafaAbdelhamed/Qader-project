<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use Illuminate\Support\Facades\Http;

class UpdateOpportunitiesModelClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opportunities = Opportunity::all();
        if ($opportunities->isEmpty()) {
            $this->command->warn('لا توجد فرص لتصنيفها.');
            return;
        }

        // جمع الساعات لكل فرصة
        $hours = $opportunities->pluck('hours')->toArray();

        // إرسال الساعات إلى API الموديل
        $response = Http::timeout(30)->post('http://localhost:5000/cluster', [
            'hours' => $hours
        ]);

        // تسجيل استجابة الموديل في اللوج
        \Log::info('Model API response:', $response->json());

        if (!$response->successful() || !isset($response['clustered_data'])) {
            $this->command->error('فشل الاتصال بـ API الموديل أو لم يتم إرجاع بيانات التصنيف.');
            return;
        }

        $clusters = $response['clustered_data'];
        $updated = 0;
        foreach ($opportunities as $index => $opportunity) {
            if (isset($clusters[$index]['cluster'])) {
                $opportunity->model_cluster = $clusters[$index]['cluster'];
                $opportunity->save();
                $updated++;
            }
        }
        $this->command->info("تم تحديث تصنيف الموديل لـ {$updated} فرصة بنجاح!");
    }
}
