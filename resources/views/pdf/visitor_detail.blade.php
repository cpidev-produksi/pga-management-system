<!DOCTYPE html>
<html>
<head>
    <title>Detail Visitor - {{ $visitor->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            line-height: 1.6;
            margin: 0;
            padding: 24px 32px;
            background: #fff;
        }

        /* ── KOP ── */
        .kop {
            display: table;
            width: 100%;
            border-bottom: 2px solid #c8102e;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .kop-left {
            display: table-cell;
            vertical-align: middle;
            width: 64px;
        }
        .kop-logo {
            width: 52px;
            height: 52px;
        }
        .kop-center {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 12px;
        }
        .kop-company {
            font-size: 17px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .kop-plant {
            font-size: 13px;
            color: #c8102e;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        .kop-doc {
            font-size: 10px;
            color: #888;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }
        .kop-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 120px;
        }
        .kop-id-label {
            font-size: 9px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-id-val {
            font-size: 10px;
            color: #555;
            word-break: break-all;
        }
        .kop-date {
            font-size: 10px;
            color: #888;
            margin-top: 4px;
        }

        /* ── STATUS BAR ── */
        .status-bar {
            display: table;
            width: 100%;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            margin-bottom: 20px;
            padding: 10px 14px;
        }
        .status-bar-left { display: table-cell; vertical-align: middle; }
        .status-bar-right { display: table-cell; vertical-align: middle; text-align: right; }
        .visitor-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a2e;
            margin: 0 0 2px 0;
        }
        .visitor-company {
            font-size: 11px;
            color: #666;
            margin: 0;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
        .badge-success  { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-warning  { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .badge-danger   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ── SECTION ── */
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #c8102e;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 0 5px 0;
            margin: 0 0 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; }
        .info-table th,
        .info-table td {
            padding: 7px 8px;
            vertical-align: top;
            text-align: left;
            border-bottom: 1px solid #f4f4f4;
        }
        .info-table th {
            width: 32%;
            color: #888;
            font-weight: normal;
            font-size: 11px;
            white-space: nowrap;
        }
        .info-table td {
            color: #1a1a2e;
            font-weight: 500;
            font-size: 12px;
        }

        /* ── 2-COLUMN GRID ── */
        .grid-2 { display: table; width: 100%; }
        .grid-col { display: table-cell; vertical-align: top; width: 50%; }
        .grid-col:first-child { padding-right: 12px; }
        .grid-col:last-child  { padding-left: 12px; }

        /* ── MEMBER LIST ── */
        .member-item {
            padding: 6px 0;
            border-bottom: 1px dashed #eee;
        }
        .member-item:last-child { border-bottom: none; }
        .member-name  { font-weight: bold; font-size: 12px; color: #1a1a2e; }
        .member-meta  { font-size: 10px; color: #888; margin-top: 1px; }

        /* ── VEHICLE ── */
        .vehicle-item {
            display: inline-block;
            background: #f0f2f5;
            border-radius: 4px;
            padding: 3px 8px;
            margin: 2px 3px 2px 0;
            font-size: 11px;
            color: #444;
        }
        .vehicle-plate {
            font-weight: bold;
            color: #1a1a2e;
        }

        /* ── HIGHLIGHT ── */
        .text-red    { color: #c8102e; font-weight: bold; }
        .text-muted  { color: #999; font-style: italic; }
        .text-green  { color: #198754; font-weight: bold; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 32px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; font-size: 9px; color: #bbb; vertical-align: bottom; }
        .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #bbb; vertical-align: bottom; }
    </style>
</head>
<body>

{{-- ══ KOP SURAT ══ --}}
@php
    $plant       = $visitor->plant ?? null;
    $plantName   = $plant ? 'Plant ' . $plant->name : 'PT. Charoen Pokphand Indonesia';
    $plantLoc    = $plant?->location ?? '';
    $plantLabel  = $plantLoc ? $plantName . ' — ' . $plantLoc : $plantName;

    $statusLabel = !is_null($visitor->checkout_at)
        ? ['label' => 'Sudah Checkout', 'class' => 'badge-danger']
        : ($visitor->status == 1
            ? ['label' => 'Sudah Masuk (Scan)',  'class' => 'badge-success']
            : ['label' => 'Belum Scan',           'class' => 'badge-warning']);
@endphp

<div class="kop">
    <div class="kop-left">
        <img class="kop-logo" src="{{ public_path('assets/img/logo-cpi.png') }}" alt="CPI">
    </div>
    <div class="kop-center">
        <p class="kop-company">PT. Charoen Pokphand Indonesia</p>
        <p class="kop-plant">{{ $plantLabel }}</p>
        <p class="kop-doc">Data Detail Pengunjung</p>
    </div>
    <div class="kop-right">
        <div class="kop-id-label">ID Kunjungan</div>
        <div class="kop-id-val">{{ substr($visitor->uuid, 0, 18) }}…</div>
        <div class="kop-date">{{ now()->format('d M Y') }}</div>
    </div>
</div>

{{-- ══ STATUS BAR ══ --}}
<div class="status-bar">
    <div class="status-bar-left">
        <p class="visitor-name">{{ $visitor->name }}</p>
        <p class="visitor-company">{{ $visitor->company_name }}</p>
    </div>
    <div class="status-bar-right">
        <span class="badge {{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span>
    </div>
</div>

{{-- ══ 2-KOLOM: INFO PRIBADI + DETAIL KUNJUNGAN ══ --}}
<div class="grid-2">
    <div class="grid-col">
        <div class="section">
            <div class="section-title">Informasi Pribadi</div>
            <table class="info-table">
                <tr>
                    <th>No. Identitas</th>
                    <td>{{ $visitor->identity_number }}</td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td>{{ $visitor->age }} tahun</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $visitor->phone_number }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $visitor->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $visitor->address }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="grid-col">
        <div class="section">
            <div class="section-title">Detail Kunjungan</div>
            <table class="info-table">
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $visitor->visit_datetime->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Pukul</th>
                    <td>{{ $visitor->visit_datetime->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Tipe Kunjungan</th>
                    <td>{{ $visitor->visit_type }}</td>
                </tr>
                <tr>
                    <th>Karyawan Dituju</th>
                    <td>{{ $visitor->intended_employee ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Keperluan</th>
                    <td>{{ $visitor->purpose }}</td>
                </tr>
                @if($visitor->purpose_note)
                <tr>
                    <th>Catatan</th>
                    <td>{{ $visitor->purpose_note }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- ══ BARIS 2: AKSES & KENDARAAN ══ --}}
<div class="grid-2">
    <div class="grid-col">
        <div class="section">
            <div class="section-title">Akses & Kategori</div>
            <table class="info-table">
                <tr>
                    <th>Masuk Produksi</th>
                    <td>
                        @if($visitor->is_production)
                            <span class="text-red">YA - Area Terbatas</span>
                        @else
                            <span class="text-green">Tidak - Area Kantor</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tipe Grup</th>
                    <td>{{ $visitor->group_type }}</td>
                </tr>
                @if($visitor->special_category)
                <tr>
                    <th>Kategori Khusus</th>
                    <td>{{ $visitor->special_category }}</td>
                </tr>
                @endif
                @if($visitor->special_needs)
                <tr>
                    <th>Kebutuhan Khusus</th>
                    <td>{{ $visitor->special_needs }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="grid-col">
        <div class="section">
            <div class="section-title">Log Petugas</div>
            <table class="info-table">
                <tr>
                    <th>Pendaftaran</th>
                    <td>{{ $visitor->created_at->format('d/m/Y H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Check-in</th>
                    <td>
                        @if($visitor->status == 1)
                            {{ $visitor->scanner->name ?? '-' }}<br>
                            <span style="font-size:10px;color:#888">{{ $visitor->updated_at->format('d/m/Y H:i') }} WIB</span>
                        @else
                            <span class="text-muted">Belum masuk</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Check-out</th>
                    <td>
                        @if($visitor->checkout_at)
                            {{ $visitor->checkouter->name ?? '-' }}<br>
                            <span style="font-size:10px;color:#888">{{ \Carbon\Carbon::parse($visitor->checkout_at)->format('d/m/Y H:i') }} WIB</span>
                        @else
                            <span class="text-muted">Belum keluar</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- ══ KENDARAAN ══ --}}
@if(!empty($visitor->vehicles) && count($visitor->vehicles) > 0)
<div class="section">
    <div class="section-title">Kendaraan</div>
    @foreach($visitor->vehicles as $v)
        <span class="vehicle-item">
            {{ $v['type'] ?? 'Kendaraan' }} — <span class="vehicle-plate">{{ $v['number'] ?? '-' }}</span>
        </span>
    @endforeach
</div>
@endif

{{-- ══ ANGGOTA ROMBONGAN ══ --}}
@if(!empty($visitor->group_members) && count($visitor->group_members) > 0)
<div class="section">
    <div class="section-title">Anggota Rombongan ({{ count($visitor->group_members) }} orang)</div>
    @foreach($visitor->group_members as $i => $m)
    <div class="member-item">
        <div class="member-name">{{ ($i+1) }}. {{ is_array($m) ? ($m['name'] ?? '-') : $m }}</div>
        @if(is_array($m))
        <div class="member-meta">
            ID: {{ $m['id'] ?? '-' }} &nbsp;·&nbsp;
            Usia: {{ $m['age'] ?? '-' }} thn &nbsp;·&nbsp;
            HP: {{ $m['phone'] ?? '-' }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif

{{-- ══ FOOTER ══ --}}
<div class="footer">
    <div class="footer-left">
        ID: {{ $visitor->uuid }}
    </div>
    <div class="footer-right">
        Dicetak otomatis · Sistem PGA Management System · {{ now()->format('d F Y, H:i') }} WIB
    </div>
</div>

</body>
</html>