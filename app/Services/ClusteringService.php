<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClusteringService
{
    protected $apiUrl = 'http://localhost:5000/cluster';

    /**
     * إرسال بيانات الساعات إلى API الموديل للحصول على التصنيف
     */
    public function clusterOpportunities($hoursData)
    {
        try {
            $response = Http::timeout(30)->post($this->apiUrl, [
                'hours' => $hoursData
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Clustering API error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Clustering API exception: ' . $e->getMessage());
            return null;
        }
    }

        /**
     * تصنيف فرصة واحدة بناءً على عدد الساعات
     */
    public function clusterSingleOpportunity($hours)
    {
        $result = $this->clusterOpportunities([$hours]);

        if ($result && isset($result['clustered_data'][0])) {
            return [
                'cluster' => $result['clustered_data'][0]['cluster'],
                'hours' => $hours,
                'category' => $this->getClusterCategory($result['clustered_data'][0]['cluster'])
            ];
        }

        return null;
    }

    /**
     * الحصول على وصف التصنيف
     */
    public function getClusterCategory($cluster)
    {
        $categories = [
            0 => 'قصيرة المدى (1-3 أيام)',
            1 => 'متوسطة المدى (4-7 أيام)',
            2 => 'طويلة المدى (أسبوع أو أكثر)'
        ];

        return $categories[$cluster] ?? 'غير محدد';
    }

    /**
     * الحصول على نطاق الساعات لكل تصنيف
     */
    public function getClusterHoursRange($cluster)
    {
        $ranges = [
            0 => ['min' => 1, 'max' => 24, 'description' => '1-24 ساعة'],
            1 => ['min' => 25, 'max' => 56, 'description' => '25-56 ساعة'],
            2 => ['min' => 57, 'max' => 1000, 'description' => '57+ ساعة']
        ];

        return $ranges[$cluster] ?? null;
    }

    /**
     * تصنيف مجموعة من الفرص
     */
    public function clusterMultipleOpportunities($opportunities)
    {
        $hoursData = collect($opportunities)->pluck('hours')->toArray();

        return $this->clusterOpportunities($hoursData);
    }

    /**
     * الحصول على ملخص التصنيف
     */
    public function getClusterSummary($opportunities)
    {
        $result = $this->clusterMultipleOpportunities($opportunities);

        if ($result && isset($result['cluster_summary'])) {
            return $result['cluster_summary'];
        }

        return null;
    }
}
