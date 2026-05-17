<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PublicPollController extends Controller
{
    public function show(string $slug)
    {
        $poll = Poll::where('slug', $slug)->with('options')->firstOrFail();

        if (!$poll->is_active) {
            return view('public.poll-inactive', compact('poll'));
        }

        return view('public.poll-enter', compact('poll'));
    }

    public function checkKey(Request $request, string $slug)
    {
        $poll = Poll::where('slug', $slug)->with('options')->firstOrFail();

        if (!$poll->is_active) {
            return back()->withErrors(['voter_key' => 'Polling ini sedang tidak aktif.']);
        }

        $request->validate([
            'voter_key' => 'required|string|max:100',
        ]);

        $voterKey = trim($request->voter_key);

        if ($poll->hasVoted($voterKey)) {
            return back()->with('already_voted', true)->with('voter_key', $voterKey)->withInput();
        }

        // Store in session so vote page can confirm identity
        session(['voter_key_' . $poll->id => $voterKey]);

        return view('public.poll-vote', compact('poll', 'voterKey'));
    }

    public function vote(Request $request, string $slug)
    {
        $poll = Poll::where('slug', $slug)->with('options')->firstOrFail();

        if (!$poll->is_active) {
            return response()->json(['success' => false, 'message' => 'Polling tidak aktif.'], 403);
        }

        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
            'voter_key'      => 'required|string|max:100',
        ]);

        $voterKey = trim($request->voter_key);

        // Validate session
        if (session('voter_key_' . $poll->id) !== $voterKey) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid. Silakan mulai ulang.'], 403);
        }

        if ($poll->hasVoted($voterKey)) {
            return response()->json(['success' => false, 'message' => 'Anda sudah pernah memilih pada polling ini!'], 409);
        }

        $option = PollOption::where('id', $request->poll_option_id)->where('poll_id', $poll->id)->firstOrFail();

        PollVote::create([
            'poll_id'        => $poll->id,
            'poll_option_id' => $option->id,
            'voter_key'      => $voterKey,
            'voter_name'     => $request->voter_name,
            'ip_address'     => $request->ip(),
        ]);

        // Clear session key after vote
        session()->forget('voter_key_' . $poll->id);

        return response()->json([
            'success' => true,
            'message' => 'Suara Anda berhasil dicatat!',
        ]);
    }

    public function result(string $slug)
    {
        $poll = Poll::where('slug', $slug)->with(['options.votes'])->firstOrFail();
        return view('public.poll-result', compact('poll'));
    }
}
