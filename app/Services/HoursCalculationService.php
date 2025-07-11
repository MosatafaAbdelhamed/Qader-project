<?php

namespace App\Services;

use Carbon\Carbon;

class HoursCalculationService
{
    /**
     * حساب عدد ساعات التطوع بناءً على تاريخ البداية والنهاية
     * نفترض أن كل يوم يحتوي على 8 ساعات عمل
     */
    public function calculateHours($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return null;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // حساب عدد الأيام
        $days = $start->diffInDays($end) + 1; // +1 لتضمين يوم البداية

        // نفترض أن كل يوم يحتوي على 8 ساعات عمل
        $hoursPerDay = 8;

        return $days * $hoursPerDay;
    }

    /**
     * حساب عدد الساعات مع إمكانية تحديد ساعات العمل اليومية
     */
    public function calculateHoursWithCustomRate($startDate, $endDate, $hoursPerDay = 8)
    {
        if (!$startDate || !$endDate) {
            return null;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // حساب عدد الأيام
        $days = $start->diffInDays($end) + 1;

        return $days * $hoursPerDay;
    }
}
