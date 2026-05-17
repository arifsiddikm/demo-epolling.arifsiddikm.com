<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('polls');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['polls' => fn($q) => $q->withCount('votes')->latest()]);
        $totalVotes = \App\Models\PollVote::whereHas('poll', fn($q) => $q->where('user_id', $user->id))->count();
        return view('admin.users.show', compact('user', 'totalVotes'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => 'Status user berhasil diubah.',
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus admin.'], 403);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
    }
}
