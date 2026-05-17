<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminUsersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return User::where('role', 'user')->withCount('polls')->latest()->get()->map(function ($user, $i) {
            return [
                'No'      => $i + 1,
                'Nama'    => $user->name,
                'Email'   => $user->email,
                'Telepon' => $user->phone ?? '-',
                'Polling' => $user->polls_count,
                'Status'  => $user->is_active ? 'Aktif' : 'Nonaktif',
                'Daftar'  => $user->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Telepon', 'Jumlah Polling', 'Status', 'Tanggal Daftar'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
