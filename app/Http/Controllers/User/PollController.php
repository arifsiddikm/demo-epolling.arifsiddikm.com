<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::where('user_id', Auth::id())
            ->withCount('votes')
            ->latest()->paginate(10);
        return view('user.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('user.polls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',
            'image'                   => 'nullable|image|max:2048',
            'primary_key_label'       => 'required|string|max:100',
            'primary_key_placeholder' => 'required|string|max:100',
            'options'                 => 'required|array|min:2',
            'options.*.name'          => 'required|string|max:255',
            'options.*.image'         => 'nullable|image|max:2048',
            'options.*.description'   => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('polls', 'public');
        }

        $poll = Poll::create([
            'user_id'                 => Auth::id(),
            'title'                   => $request->title,
            'description'             => $request->description,
            'image'                   => $imagePath,
            'primary_key_label'       => $request->primary_key_label,
            'primary_key_placeholder' => $request->primary_key_placeholder,
            'is_active'               => $request->boolean('is_active', true),
            'start_date'              => $request->start_date,
            'end_date'                => $request->end_date,
        ]);

        foreach ($request->options as $i => $opt) {
            $optImage = null;
            if (isset($opt['image']) && $opt['image'] instanceof \Illuminate\Http\UploadedFile) {
                $optImage = $opt['image']->store('poll-options', 'public');
            }
            PollOption::create([
                'poll_id'     => $poll->id,
                'name'        => $opt['name'],
                'description' => $opt['description'] ?? null,
                'image'       => $optImage,
                'order'       => $i,
            ]);
        }

        return redirect()->route('user.polls.index')->with('success', 'Polling berhasil dibuat!');
    }

    public function show(Poll $poll)
    {
        $this->authorize('view', $poll);
        $poll->load(['options.votes', 'votes']);
        return view('user.polls.show', compact('poll'));
    }

    public function edit(Poll $poll)
    {
        $this->authorize('update', $poll);
        $poll->load('options');
        return view('user.polls.edit', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        $this->authorize('update', $poll);

        $request->validate([
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',
            'image'                   => 'nullable|image|max:2048',
            'primary_key_label'       => 'required|string|max:100',
            'primary_key_placeholder' => 'required|string|max:100',
        ]);

        $data = $request->only(['title', 'description', 'primary_key_label', 'primary_key_placeholder', 'start_date', 'end_date']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($poll->image) Storage::disk('public')->delete($poll->image);
            $data['image'] = $request->file('image')->store('polls', 'public');
        }

        $poll->update($data);
        return redirect()->route('user.polls.show', $poll)->with('success', 'Polling berhasil diperbarui!');
    }

    public function toggleStatus(Poll $poll)
    {
        $this->authorize('update', $poll);
        $poll->update(['is_active' => !$poll->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $poll->is_active,
            'message' => 'Status polling berhasil diubah.',
        ]);
    }

    public function destroy(Poll $poll)
    {
        $this->authorize('delete', $poll);
        if ($poll->image) Storage::disk('public')->delete($poll->image);
        $poll->delete();
        return redirect()->route('user.polls.index')->with('success', 'Polling berhasil dihapus!');
    }

    public function recap(Poll $poll)
    {
        $this->authorize('view', $poll);
        $poll->load(['options.votes', 'votes.option']);
        return view('user.polls.recap', compact('poll'));
    }
}
