<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; }
    .header { background: #dc2626; color: white; padding: 20px 30px; margin-bottom: 20px; }
    .header h1 { font-size: 22px; font-weight: bold; }
    .header p { font-size: 11px; opacity: 0.85; margin-top: 3px; }
    .meta { padding: 0 30px 20px; border-bottom: 2px solid #dc2626; margin-bottom: 20px; }
    .meta h2 { font-size: 16px; font-weight: bold; color: #dc2626; margin-bottom: 10px; }
    .meta-grid { display: flex; gap: 30px; flex-wrap: wrap; }
    .meta-item { font-size: 11px; }
    .meta-item strong { display: block; color: #666; font-size: 10px; text-transform: uppercase; margin-bottom: 2px; }
    .section { padding: 0 30px 20px; }
    .section h3 { font-size: 13px; font-weight: bold; color: #dc2626; border-left: 3px solid #dc2626; padding-left: 8px; margin-bottom: 10px; }
    .results-table { width: 100%; border-collapse: collapse; }
    .results-table th { background: #1a1a1a; color: white; padding: 8px 12px; text-align: left; font-size: 10px; text-transform: uppercase; }
    .results-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 11px; }
    .results-table tr:nth-child(even) td { background: #f9fafb; }
    .bar-bg { background: #eee; height: 8px; border-radius: 4px; }
    .bar-fill { background: #dc2626; height: 8px; border-radius: 4px; }
    .badge-active { background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
    .badge-inactive { background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 12px; font-size: 10px; }
    .votes-table { width: 100%; border-collapse: collapse; }
    .votes-table th { background: #374151; color: white; padding: 7px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
    .votes-table td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
    .votes-table tr:nth-child(even) td { background: #f9fafb; }
    .footer { padding: 15px 30px; border-top: 1px solid #eee; font-size: 10px; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>e-Polling — Rekap Hasil Voting</h1>
    <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
</div>

<div class="meta">
    <h2>{{ $poll->title }}</h2>
    @if($poll->description)
    <p style="font-size:11px;color:#555;margin-bottom:10px;">{{ strip_tags($poll->description) }}</p>
    @endif
    <div class="meta-grid">
        <div class="meta-item">
            <strong>Penyelenggara</strong>{{ $poll->user->name ?? '-' }}
        </div>
        <div class="meta-item">
            <strong>Kunci Pemilih</strong>{{ $poll->primary_key_label }}
        </div>
        <div class="meta-item">
            <strong>Total Suara</strong>{{ $poll->total_votes }}
        </div>
        <div class="meta-item">
            <strong>Status</strong>
            @if($poll->is_active)<span class="badge-active">Aktif</span>@else<span class="badge-inactive">Nonaktif</span>@endif
        </div>
        <div class="meta-item">
            <strong>Dibuat</strong>{{ $poll->created_at->format('d M Y') }}
        </div>
    </div>
</div>

<div class="section">
    <h3>Hasil Per Pilihan</h3>
    <table class="results-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pilihan</th>
                <th>Jumlah Suara</th>
                <th>Persentase</th>
                <th style="width:150px">Grafik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($poll->options->sortByDesc(fn($o) => $o->vote_count) as $i => $option)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $option->name }}</strong>@if($option->description)<br><span style="color:#999;font-size:10px">{{ $option->description }}</span>@endif</td>
                <td><strong>{{ $option->vote_count }}</strong> suara</td>
                <td><strong>{{ $option->percentage }}%</strong></td>
                <td>
                    <div class="bar-bg"><div class="bar-fill" style="width:{{ $option->percentage }}%"></div></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($poll->votes->count() > 0)
<div class="section">
    <h3>Data Pemilih</h3>
    <table class="votes-table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ $poll->primary_key_label }}</th>
                <th>Nama</th>
                <th>Pilihan</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($poll->votes as $i => $vote)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $vote->voter_key }}</td>
                <td>{{ $vote->voter_name ?? '-' }}</td>
                <td>{{ $vote->option->name ?? '-' }}</td>
                <td>{{ $vote->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">
    Dokumen ini dibuat otomatis oleh e-Polling &bull; {{ config('app.url') }}
</div>
</body>
</html>
