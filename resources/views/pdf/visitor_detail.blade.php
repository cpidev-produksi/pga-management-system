<!DOCTYPE html>
<html>
<head>
    <title>Detail Visitor - {{ $visitor->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* --- RESET & GLOBAL --- */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* --- HEADER (KOP SURAT) --- */
        .header-container {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .doc-title {
            font-size: 16px;
            color: #7f8c8d;
            font-weight: normal;
            margin-top: 0;
        }
        .meta-info {
            margin-top: 10px;
            font-size: 11px;
            color: #555;
        }

        /* --- SECTION HEADERS --- */
        .section-title {
            background-color: #f0f2f5;
            color: #2c3e50;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: bold;
            border-left: 5px solid #2c3e50;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* --- TABLES --- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 10px;
            vertical-align: top;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            width: 35%;
            color: #555;
            font-weight: bold;
            background-color: #fff;
        }
        td {
            color: #000;
            font-weight: 500;
        }
        tr:nth-child(even) th, 
        tr:nth-child(even) td {
            background-color: #fcfcfc;
        }

        /* --- BADGES (STATUS) --- */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }
        /* Tambahan Badge Merah untuk Checkout */
        .badge-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        /* --- LIST STYLING --- */
        ul.custom-list, ol.custom-list {
            margin: 0;
            padding-left: 20px;
        }
        .member-item {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #ddd;
        }
        .member-item:last-child {
            border-bottom: none;
        }
        .member-name {
            font-weight: bold;
            font-size: 13px;
            color: #2c3e50;
        }
        .member-detail {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }

        /* --- FOOTER --- */
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="company-name">PT. Charoen Pokphand Indonesia</div>
        <div class="doc-title">Plant Ngoro - Mojokerto</div>
        <div class="meta-info">
            DATA DETAIL PENGUNJUNG | ID: <strong>{{ $visitor->uuid }}</strong>
        </div>
    </div>

    <div class="section-title">Informasi Pribadi</div>
    <table>
        <tr>
            <th>Nama Lengkap</th>
            <td><strong>{{ $visitor->name }}</strong></td>
        </tr>
        <tr>
            <th>No. Identitas (KTP/SIM)</th>
            <td>{{ $visitor->identity_number }}</td>
        </tr>
        <tr>
            <th>Usia</th>
            <td>{{ $visitor->age }} Tahun</td>
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
        <tr>
            <th>Perusahaan Asal</th>
            <td>{{ $visitor->company_name }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Kunjungan</div>
    <table>
        <tr>
            <th>Tanggal & Jam Berkunjung</th>
            <td>{{ $visitor->visit_datetime->format('d F Y') }} <span style="color:#888;">pukul</span> {{ $visitor->visit_datetime->format('H:i') }} WIB</td>
        </tr>
        <tr>
            <th>Tipe Kunjungan</th>
            <td>{{ $visitor->visit_type }}</td>
        </tr>
        <tr>
            <th>Karyawan yang Dituju</th>
            <td>{{ $visitor->intended_employee ?? '-' }}</td>
        </tr>
        <tr>
            <th>Keperluan</th>
            <td>{{ $visitor->purpose }}</td>
        </tr>
        <tr>
            <th>Catatan Keperluan</th>
            <td>{{ $visitor->purpose_note }}</td>
        </tr>
        <tr>
            <th>Masuk Produksi?</th>
            <td>
                {{-- PERBAIKAN LOGIC BLADE & STYLE --}}
                @if($visitor->is_production)
                    <span style="color: #d32f2f; font-weight: bold;">
                        {{-- SVG diganti style inline agar aman di PDF --}}
                        <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="icon-text">YA (Area Terbatas)</span>
                    </span>
                @else
                    <span style="color: #6c757d;">
                        <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="icon-text">Tidak / Area Kantor</span>
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Kategori Khusus</th>
            <td>{{ $visitor->special_category ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kebutuhan Khusus</th>
            <td>{{ $visitor->special_needs ?? '-' }}</td>
        </tr>
        <tr>
            <th>Akses Internet</th>
            <td>{{ $visitor->internet ? 'Ya (Diperbolehkan)' : 'Tidak' }}</td>
        </tr>
    </table>

    <div class="section-title">Status & Waktu</div>
    <table>
        <tr>
            <th>Status Saat Ini</th>
            <td>
                {{-- LOGIKA BARU: Cek Checkout dulu, baru cek Status Scan --}}
                @if(!is_null($visitor->checkout_at))
                    <span class="badge badge-danger">Sudah Checkout (Keluar)</span>
                @elseif($visitor->status == 1)
                    <span class="badge badge-success">Sudah Scan (Masuk)</span>
                @else
                    <span class="badge badge-warning">Belum Scan</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Waktu Dibuat</th>
            <td>{{ $visitor->created_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        <tr>
            <th>Waktu Checkout</th>
            <td>
                @if($visitor->checkout_at)
                    {{ $visitor->checkout_at->format('d/m/Y H:i:s') }}
                @else
                    <span style="color:#999; font-style:italic;">Belum melakukan checkout</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">Kendaraan & Rombongan</div>
    <table>
        <tr>
            <th>Tipe Grup</th>
            <td>{{ $visitor->group_type }}</td>
        </tr>
        <tr>
            <th>Kendaraan</th>
            <td>
                @if(!empty($visitor->vehicles) && count($visitor->vehicles) > 0)
                    <ul class="custom-list">
                    @foreach($visitor->vehicles as $vehicle)
                        <li>
                            <strong>{{ $vehicle['type'] ?? 'Kendaraan' }}</strong> 
                            — Plat: {{ $vehicle['number'] ?? '-' }}
                        </li>
                    @endforeach
                    </ul>
                @else
                    <span style="color:#888;">Tidak membawa kendaraan</span>
                @endif
            </td>
        </tr>
        
        @if(!empty($visitor->group_members) && count($visitor->group_members) > 0)
        <tr>
            <th>Anggota Rombongan ({{ count($visitor->group_members) }} Orang)</th>
            <td>
                <ol class="custom-list">
                @foreach($visitor->group_members as $member)
                    <li class="member-item">
                        @if(is_array($member))
                            <div class="member-name">{{ $member['name'] ?? '-' }}</div>
                            <div class="member-detail">
                                ID: {{ $member['id'] ?? '-' }} &nbsp;|&nbsp; 
                                Usia: {{ $member['age'] ?? '-' }} thn &nbsp;|&nbsp; 
                                HP: {{ $member['phone'] ?? '-' }}
                            </div>
                        @else
                            <div class="member-name">{{ $member }}</div>
                        @endif
                    </li>
                @endforeach
                </ol>
            </td>
        </tr>
        @endif

        <tr>
            <th>Kontak Darurat</th>
            <td>{{ $visitor->phone_number_emrg }}</td>
        </tr>
    </table>
    <div class="section-title">Status & Log Petugas</div>
    <table>
        <tr>
            <th>Check-in (Scan)</th>
            <td>
                @if($visitor->status == 1)
                    <strong>{{ $visitor->scanner->name ?? '-' }}</strong> 
                    <span style="color:#888; font-size: 11px;">— {{ $visitor->updated_at->format('d/m/Y H:i') }} WIB</span>
                @else
                    <span style="color:#999; font-style:italic;">Belum masuk</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Check-out</th>
            <td>
                @if($visitor->checkout_at)
                    <strong>{{ $visitor->checkouter->name ?? '-' }}</strong> 
                    <span style="color:#888; font-size: 11px;">— {{ \Carbon\Carbon::parse($visitor->checkout_at)->format('d/m/Y H:i') }} WIB</span>
                @else
                    <span style="color:#999; font-style:italic;">Belum keluar</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Waktu Pendaftaran</th>
            <td>{{ $visitor->created_at->format('d/m/Y H:i') }} WIB</td>
        </tr>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem PGA Digital pada {{ date('d F Y H:i') }} WIB
    </div>

</body>
</html>