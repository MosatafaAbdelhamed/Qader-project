<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunity;
use Illuminate\Support\Facades\Http;
use App\Models\Organization;
use App\Models\User;
use App\Services\HoursCalculationService;
use App\Services\ClusteringService;
use Illuminate\Support\Facades\Auth;


class OpportunitiesController extends Controller
{
    protected $hoursCalculationService;
    protected $clusteringService;

    public function __construct(HoursCalculationService $hoursCalculationService, ClusteringService $clusteringService)
    {
        $this->hoursCalculationService = $hoursCalculationService;
        $this->clusteringService = $clusteringService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opportunities = Opportunity::select('opportunity_id', 'title', 'description', 'category_id', 'organization_id', 'start', 'end', 'hours')
        ->with(['category', 'organization'])->paginate(10);

        foreach ($opportunities as $opportunity) {
            if (!$opportunity->hours && $opportunity->start && $opportunity->end) {
                $hours = $this->hoursCalculationService->calculateHours($opportunity->start, $opportunity->end);
                if ($hours) {
                    $opportunity->update(['hours' => $hours]);
                }
            }
        }

        return response()->json($opportunities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $organization = auth()->user();

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,category_id',
        'start' => 'nullable|date',
        'end' => 'nullable|date|after_or_equal:start',
        'hours' => 'nullable|integer|min:1',
    ]);

    $data['organization_id'] = $organization->organization_id;

    if (!isset($data['hours']) && isset($data['start']) && isset($data['end'])) {
        $data['hours'] = $this->hoursCalculationService->calculateHours($data['start'], $data['end']);
    }

    $opportunity = Opportunity::create($data);

    if (!$opportunity->hours && $opportunity->start && $opportunity->end) {
        $start = \Carbon\Carbon::parse($opportunity->start);
        $end = \Carbon\Carbon::parse($opportunity->end);
        $days = $start->diffInDays($end) + 1;
        $opportunity->hours = $days * 8;
        $opportunity->save();
    }

    if ($opportunity->hours) {
        $response = Http::post('http://localhost:5000/cluster', [
            'hours' => [$opportunity->hours],
        ]);
        if ($response->successful() && isset($response['clustered_data'][0]['cluster'])) {
            $opportunity->model_cluster = $response['clustered_data'][0]['cluster'];
            $opportunity->save();
        }
    }

    if ($opportunity->hours) {
        $clusterResult = $this->clusteringService->clusterSingleOpportunity($opportunity->hours);
        if ($clusterResult !== null) {
            $opportunity->cluster = $clusterResult['cluster'];
            $opportunity->cluster_category = $clusterResult['category'];
        }
    }

        return response()->json([
        'message' => 'Opportunity created successfully',
        'opportunity' => $opportunity,
        'cluster' => $clusterResult ?? null
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function myOpportunities(Request $request)
    {
    $organization = Auth::user();

    if (!$organization || !($organization instanceof Organization)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $opportunities = Opportunity::where('organization_id', $organization->organization_id)
        ->with(['category', 'organization'])
        ->paginate(10);

    return response()->json($opportunities, 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $opportunity_id)
{
    $organization = auth()->user();
    $opportunity = Opportunity::where('opportunity_id', $opportunity_id)
        ->where('organization_id', $organization->organization_id)
        ->first();

    if (!$opportunity) {
        return response()->json(['message' => 'Opportunity not found or unauthorized'], 404);
    }

    $data = $request->validate([
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'category_id' => 'sometimes|exists:categories,category_id',
        'start' => 'nullable|date',
        'end' => 'nullable|date|after_or_equal:start',
    ]);

    $opportunity->update($data);
    return response()->json(['message' => 'Opportunity updated successfully', 'opportunity' => $opportunity], 200);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($opportunity_id)
{
    $organization = auth()->user();
    $opportunity = Opportunity::where('opportunity_id', $opportunity_id)
        ->where('organization_id', $organization->organization_id)
        ->first();

    if (!$opportunity) {
        return response()->json(['message' => 'Opportunity not found or unauthorized'], 404);
    }

    $opportunity->delete();
    return response()->json(['message' => 'Opportunity deleted successfully'], 200);
}

    public function search(Request $request)
    {
    $query = Opportunity::with(['category', 'organization']);

    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->has('title')) {
        $query->where('title', 'LIKE', '%' . $request->title . '%');
    }

    if ($request->has('start')) {
        $query->whereDate('start', $request->start);
    }

    if ($request->has('keyword')) {
        $keyword = $request->keyword;

        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhereHas('organization', function ($orgQuery) use ($keyword) {
                $orgQuery->where('location', 'LIKE', "%{$keyword}%");
            });
        });
    }

    if ($request->has('hours_min') || $request->has('hours_max')) {
        $query->whereNotNull('hours');

        if ($request->has('hours_min')) {
            $query->where('hours', '>=', $request->hours_min);
        }

        if ($request->has('hours_max')) {
            $query->where('hours', '<=', $request->hours_max);
        }
    }

    if ($request->has('sort_by')) {
        $sortBy = $request->sort_by;
        $sortOrder = $request->get('sort_order', 'asc');

        $validColumns = ['title', 'start', 'hours', 'created_at'];
        if (in_array($sortBy, $validColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        }
    }

    $opportunities = $query->paginate(10);

    foreach ($opportunities as $opportunity) {
        if ($opportunity->hours) {
            $clusterResult = $this->clusteringService->clusterSingleOpportunity($opportunity->hours);
            if ($clusterResult !== null) {
                $opportunity->cluster = $clusterResult['cluster'];
                $opportunity->cluster_category = $clusterResult['category'];
            }
        }
    }

    return response()->json($opportunities, 200);
}


    public function getClusters()
    {
        $opportunities = Opportunity::whereNotNull('hours')->get();

        if ($opportunities->isEmpty()) {
            return response()->json([
                'message' => 'No opportunities with hours found',
                'clusters' => []
            ], 200);
        }

        $clusteringResult = $this->clusteringService->clusterMultipleOpportunities($opportunities);

        if (!$clusteringResult) {
            return response()->json([
                'message' => 'Failed to get clustering results',
                'clusters' => []
            ], 500);
        }

        foreach ($opportunities as $index => $opportunity) {
            if (isset($clusteringResult['clustered_data'][$index])) {
                $cluster = $clusteringResult['clustered_data'][$index]['cluster'];
                $opportunity->cluster = $cluster;
                $opportunity->cluster_category = $this->clusteringService->getClusterCategory($cluster);
                $opportunity->cluster_range = $this->clusteringService->getClusterHoursRange($cluster);
            }
        }

        return response()->json([
            'message' => 'Clustering completed successfully',
            'cluster_summary' => $clusteringResult['cluster_summary'] ?? null,
            'silhouette_score' => $clusteringResult['silhouette_score'] ?? null,
            'opportunities' => $opportunities,
            'clustered_data' => $clusteringResult['clustered_data'] ?? []
        ], 200);
    }

    public function searchByCluster($cluster, Request $request)
    {
        if (!in_array((int)$cluster, [0, 1, 2])) {
            return response()->json([
                'message' => 'Invalid cluster value. Must be 0, 1, or 2.'
            ], 400);
        }

        $query = Opportunity::where('model_cluster', $cluster);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $opportunities = $query->paginate(10);

        return response()->json([
            'message' => 'Filtered opportunities by cluster and category',
            'cluster' => (int)$cluster,
            'category_id' => $request->category_id ?? null,
            'opportunities' => $opportunities->items(),
            'current_page' => $opportunities->currentPage(),
            'last_page' => $opportunities->lastPage(),
            'per_page' => $opportunities->perPage(),
            'total' => $opportunities->total(),
            'links' => [
                'next' => $opportunities->nextPageUrl(),
                'prev' => $opportunities->previousPageUrl(),
            ],
        ], 200);
    }

    public function getClusterSummary()
    {
        $opportunities = Opportunity::whereNotNull('hours')->get();

        if ($opportunities->isEmpty()) {
            return response()->json([
                'message' => 'No opportunities with hours found',
                'summary' => []
            ], 200);
        }

        $clusteringResult = $this->clusteringService->clusterMultipleOpportunities($opportunities);

        if (!$clusteringResult) {
            return response()->json([
                'message' => 'Failed to get clustering results',
                'summary' => []
            ], 500);
        }

        $summary = [];
        $clusterCounts = $clusteringResult['cluster_summary']['cluster_counts'] ?? [];

        foreach ($clusterCounts as $cluster => $count) {
            $summary[] = [
                'cluster' => (int)$cluster,
                'count' => $count,
                'category' => $this->clusteringService->getClusterCategory((int)$cluster),
                'range' => $this->clusteringService->getClusterHoursRange((int)$cluster),
                'percentage' => round(($count / count($opportunities)) * 100, 2)
            ];
        }

        return response()->json([
            'message' => 'Cluster summary retrieved successfully',
            'total_opportunities' => count($opportunities),
            'silhouette_score' => $clusteringResult['silhouette_score'] ?? null,
            'summary' => $summary
        ], 200);
    }

}
