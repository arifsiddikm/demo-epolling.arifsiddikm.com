<?php

namespace App\Exports;

use App\Models\Poll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminPollsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Poll::with('user')->withCount('votes')->latest()->get()->map(function ($poll, $i) {
            return [
                'No'         => $i + 1,
                'Judul'      => $poll->title,
                'Pembuat'    => $poll->user->name ?? '-',
                'Status'     => $poll->is_active ? 'Aktif' : 'Nonaktif',
                'Suara'      => $poll->votes_count,
                'Dibuat'     => $poll->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Judul Polling', 'Pembuat', 'Status', 'Total Suara', 'Tanggal Dibuat'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
