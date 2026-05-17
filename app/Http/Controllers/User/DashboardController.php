<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalPolls = Poll::where('user_id', $user->id)->count();
        $activePolls = Poll::where('user_id', $user->id)->where('is_active', true)->count();
        $totalVotes = \App\Models\PollVote::whereHas('poll', fn($q) => $q->where('user_id', $user->id))->count();
        $recentPolls = Poll::where('user_id', $user->id)->withCount('votes')->latest()->take(5)->get();

        // Chart data: votes per poll (last 5)
        $chartLabels = $recentPolls->pluck('title')->map(fn($t) => \Str::limit($t, 20))->toArray();
        $chartData = $recentPolls->pluck('votes_count')->toArray();

        return view('user.dashboard', compact(
            'totalPolls', 'activePolls', 'totalVotes', 'recentPolls', 'chartLabels', 'chartData'
        ));
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah!');
    }
}
