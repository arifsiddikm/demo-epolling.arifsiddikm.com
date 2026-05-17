<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPolls = Poll::count();
        $activePolls = Poll::where('is_active', true)->count();
        $totalVotes = PollVote::count();

        // Monthly registrations for chart (last 6 months)
        $months = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'label' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('role', 'user')
                    ->count(),
                'polls' => Poll::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        $recentPolls = Poll::with('user')->withCount('votes')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPolls', 'activePolls', 'totalVotes', 'months', 'recentPolls'
        ));
    }
}
