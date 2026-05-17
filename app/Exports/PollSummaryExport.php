<?php

namespace App\Exports;

use App\Models\Poll;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PollSummaryExport implements FromCollection, WithHeadings, WithStyles
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        return Poll::where('user_id', $this->user->id)
            ->withCount('votes')
            ->get()
            ->map(function ($poll, $i) {
                return [
                    'No'           => $i + 1,
                    'Judul'        => $poll->title,
                    'Status'       => $poll->is_active ? 'Aktif' : 'Nonaktif',
                    'Total Suara'  => $poll->votes_count,
                    'Dibuat'       => $poll->created_at->format('d/m/Y'),
                    'Link'         => route('poll.show', $poll->slug),
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Judul Polling', 'Status', 'Total Suara', 'Tanggal Dibuat', 'Link Polling'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
