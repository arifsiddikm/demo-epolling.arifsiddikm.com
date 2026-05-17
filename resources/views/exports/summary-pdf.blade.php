<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
    .header { background: #dc2626; color: white; padding: 18px 25px; margin-bottom: 20px; }
    .header h1 { font-size: 18px; font-weight: bold; }
    .header p { font-size: 10px; opacity: 0.8; margin-top: 2px; }
    .meta { padding: 0 25px 15px; font-size: 11px; border-bottom: 1px solid #e5e7eb; margin-bottom: 15px; }
    table { width: calc(100% - 50px); border-collapse: collapse; margin: 0 25px; }
    th { background: #1f2937; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
    td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .badge-on { background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 8px; font-size: 9px; }
    .badge-off { background: #f3f4f6; color: #6b7280; padding: 2px 6px; border-radius: 8px; font-size: 9px; }
    .footer { margin-top: 20px; padding: 10px 25px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>e-Polling — Ringkasan Polling Saya</h1>
    <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
</div>
<div class="meta">
    <strong>Pengguna:</strong> {{ $user->name }} ({{ $user->email }}) &nbsp;&bull;&nbsp;
    <strong>Total Polling:</strong> {{ $polls->count() }} &nbsp;&bull;&nbsp;
    <strong>Total Suara:</strong> {{ $totalVotes }}
</div>
<table>
    <thead>
        <tr><th>#</th><th>Judul Polling</th><th>Status</th><th>Total Suara</th><th>Dibuat</th></tr>
    </thead>
    <tbody>
        @foreach($polls as $i => $poll)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $poll->title }}</strong></td>
            <td>@if($poll->is_active)<span class="badge-on">Aktif</span>@else<span class="badge-off">Off</span>@endif</td>
            <td>{{ $poll->votes_count }}</td>
            <td>{{ $poll->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">e-Polling &bull; {{ config('app.url') }}</div>
</body>
</html>
