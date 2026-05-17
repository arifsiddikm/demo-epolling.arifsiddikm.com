<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PollVotesExport;

class ExportController extends Controller
{
    public function exportPdf(Poll $poll)
    {
        $this->authorize('view', $poll);
        $poll->load(['options.votes', 'votes.option', 'user']);

        $pdf = Pdf::loadView('exports.poll-recap-pdf', compact('poll'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('rekap-polling-' . $poll->slug . '.pdf');
    }

    public function exportExcel(Poll $poll)
    {
        $this->authorize('view', $poll);
        return Excel::download(new PollVotesExport($poll), 'rekap-polling-' . $poll->slug . '.xlsx');
    }

    public function exportSummaryPdf()
    {
        $user = Auth::user();
        $polls = Poll::where('user_id', $user->id)->withCount('votes')->get();
        $totalVotes = \App\Models\PollVote::whereHas('poll', fn($q) => $q->where('user_id', $user->id))->count();

        $pdf = Pdf::loadView('exports.summary-pdf', compact('polls', 'totalVotes', 'user'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('summary-polling-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportSummaryExcel()
    {
        $user = Auth::user();
        return Excel::download(new \App\Exports\PollSummaryExport($user), 'summary-polling-' . now()->format('Y-m-d') . '.xlsx');
    }
}
