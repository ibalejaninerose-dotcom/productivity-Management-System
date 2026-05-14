<?php

namespace App\Http\Controllers;

use App\Models\GatewayLoss;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function getStats()
    {
        // ============================================================
        // SERVICE 1: GATEWAY LOSS API (Local)
        // ============================================================
        $gatewayRecords = GatewayLoss::all();
        $totalLoss = $gatewayRecords->sum('loss_amount');
        $totalRecords = $gatewayRecords->count();
        $avgLoss = $totalRecords > 0 ? $totalLoss / $totalRecords : 0;
        $providers = $gatewayRecords->pluck('provider')->unique()->count();
        
        // Get recent records
        $recentRecords = $gatewayRecords->sortByDesc('created_at')->take(5)->values();
        
        // ============================================================
        // SERVICE 2: GITHUB API (External)
        // ============================================================
        $githubResponse = Http::get('https://api.github.com/repos/laravel/laravel');
        
        if ($githubResponse->successful()) {
            $githubData = $githubResponse->json();
            $githubStats = [
                'repo_name' => $githubData['name'] ?? 'N/A',
                'full_name' => $githubData['full_name'] ?? 'N/A',
                'stars' => $githubData['stargazers_count'] ?? 0,
                'forks' => $githubData['forks_count'] ?? 0,
                'open_issues' => $githubData['open_issues_count'] ?? 0,
                'description' => $githubData['description'] ?? 'No description',
                'language' => $githubData['language'] ?? 'N/A',
                'url' => $githubData['html_url'] ?? '#'
            ];
        } else {
            $githubStats = [
                'repo_name' => 'Error',
                'full_name' => 'Unable to fetch data',
                'stars' => 0,
                'forks' => 0,
                'open_issues' => 0,
                'description' => 'GitHub API rate limit exceeded or no internet connection',
                'language' => 'N/A',
                'url' => '#'
            ];
        }
        
        // ============================================================
        // COMBINED RESPONSE (SERVICE 1 + SERVICE 2)
        // ============================================================
        return response()->json([
            'success' => true,
            'service1_gateway' => [
                'name' => 'Gateway Loss API',
                'total_records' => $totalRecords,
                'total_loss' => $totalLoss,
                'average_loss' => round($avgLoss, 2),
                'total_providers' => $providers,
                'recent_records' => $recentRecords
            ],
            'service2_github' => [
                'name' => 'GitHub API',
                'repository' => $githubStats
            ],
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}