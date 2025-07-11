<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use Carbon\Carbon;

class AddHoursToOpportunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opportunities = Opportunity::whereNull('hours')->get();
        $hoursPerDay = 8; // 8 ساعات لكل يوم

        foreach ($opportunities as $opportunity) {
            if ($opportunity->start && $opportunity->end) {
                $startDate = Carbon::parse($opportunity->start);
                $endDate = Carbon::parse($opportunity->end);

                // حساب عدد الأيام (بما في ذلك يوم البداية)
                $days = $startDate->diffInDays($endDate) + 1;

                // حساب إجمالي الساعات
                $totalHours = $days * $hoursPerDay;

                $opportunity->update(['hours' => $totalHours]);

                $this->command->info("Updated opportunity '{$opportunity->title}' with {$totalHours} hours ({$days} days × {$hoursPerDay} hours/day)");
            } else {
                $this->command->warn("Skipped opportunity '{$opportunity->title}' - missing start or end date");
            }
        }

        $updatedCount = $opportunities->whereNotNull('start')->whereNotNull('end')->count();
        $this->command->info("Successfully updated {$updatedCount} opportunities with hours calculation");
    }
}
