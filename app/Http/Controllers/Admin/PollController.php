<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index(Request $request)
    {
        $query = Poll::with('user')->withCount('votes');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $polls = $query->latest()->paginate(15);
        return view('admin.polls.index', compact('polls'));
    }

    public function show(Poll $poll)
    {
        $poll->load(['options.votes', 'user', 'votes.option']);
        return view('admin.polls.show', compact('poll'));
    }

    public function toggleStatus(Poll $poll)
    {
        $poll->update(['is_active' => !$poll->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $poll->is_active,
            'message' => 'Status polling berhasil diubah.',
        ]);
    }

    public function destroy(Poll $poll)
    {
        $poll->delete();
        return response()->json(['success' => true, 'message' => 'Polling berhasil dihapus.']);
    }
}
