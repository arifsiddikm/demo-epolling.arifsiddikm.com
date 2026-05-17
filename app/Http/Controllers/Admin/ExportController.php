<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\User;
use App\Models\PollVote;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminPollsExport;
use App\Exports\AdminUsersExport;

class ExportController extends Controller
{
    public function exportPollsPdf()
    {
        $polls = Poll::with('user')->withCount('votes')->latest()->get();
        $pdf = Pdf::loadView('exports.admin-polls-pdf', compact('polls'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('admin-polls-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportPollsExcel()
    {
        return Excel::download(new AdminPollsExport(), 'admin-polls-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportUsersPdf()
    {
        $users = User::where('role', 'user')->withCount('polls')->latest()->get();
        $pdf = Pdf::loadView('exports.admin-users-pdf', compact('users'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('admin-users-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportUsersExcel()
    {
        return Excel::download(new AdminUsersExport(), 'admin-users-' . now()->format('Y-m-d') . '.xlsx');
    }
}
