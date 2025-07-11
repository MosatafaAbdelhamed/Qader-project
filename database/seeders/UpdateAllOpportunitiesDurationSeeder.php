<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use Carbon\Carbon;

class UpdateAllOpportunitiesDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opportunities = Opportunity::all();
        $count = $opportunities->count();
        if ($count === 0) {
            $this->command->warn('لا توجد فرص لتعديلها.');
            return;
        }

        // توزيع متساوي
        $shortCount = (int)($count / 3);
        $mediumCount = (int)($count / 3);
        $longCount = $count - $shortCount - $mediumCount;

        $types = array_merge(
            array_fill(0, $shortCount, 'short'),
            array_fill(0, $mediumCount, 'medium'),
            array_fill(0, $longCount, 'long')
        );
        shuffle($types);

        $today = Carbon::today();
        $i = 0;
        foreach ($opportunities as $opportunity) {
            $type = $types[$i++];
            if ($type === 'short') {
                $days = [1, 2, 3][array_rand([1, 2, 3])];
            } elseif ($type === 'medium') {
                $days = [4, 5, 6, 7][array_rand([4, 5, 6, 7])];
            } else {
                $days = rand(8, 20);
            }
            $start = $today->copy()->addDays(rand(0, 30));
            $end = $start->copy()->addDays($days - 1);
            $opportunity->start = $start->toDateString();
            $opportunity->end = $end->toDateString();
            $opportunity->hours = $days * 8;
            $opportunity->save();
        }
        $this->command->info('تم توزيع كل الفرص إلى تلت اتلات مع ضبط التواريخ والساعات بدقة!');
    }
}
