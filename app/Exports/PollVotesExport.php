<?php

namespace App\Exports;

use App\Models\Poll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PollVotesExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected Poll $poll;

    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function collection()
    {
        return $this->poll->votes()->with('option')->get()->map(function ($vote, $i) {
            return [
                'No'            => $i + 1,
                'Voter Key'     => $vote->voter_key,
                'Nama Pemilih'  => $vote->voter_name ?? '-',
                'Pilihan'       => $vote->option->name ?? '-',
                'Waktu Memilih' => $vote->created_at->format('d/m/Y H:i'),
                'IP Address'    => $vote->ip_address ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', $this->poll->primary_key_label, 'Nama Pemilih', 'Pilihan', 'Waktu Memilih', 'IP Address'];
    }

    public function title(): string
    {
        return 'Rekap Voting';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
