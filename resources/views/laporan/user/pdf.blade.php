<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan User</title>
    <style>
        @page {
            margin: 20mm 15mm 15mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        /* HEADER */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .brand-address {
            font-size: 11px;
            line-height: 1.4;
        }

        .logo-img {
            width: 60px;
        }

        /* TITLE */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .report-date {
            font-size: 11px;
        }

        /* STATS SUMMARY */
        .stats-summary {
            border: 1px solid #000;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 11px;
            text-align: center;
        }

        .stats-summary span {
            display: inline-block;
            margin: 0 15px;
        }

        .stats-summary strong {
            font-weight: bold;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background-color: #e5e5e5;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #000;
        }

        tbody td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            border-top: 1px solid #000;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <div>
            <!-- BRAND -->
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <!-- KIRI -->
                <div style="display:flex; align-items:center; gap:14px;">
                    <img src="{{ public_path('img/logo.png') }}" class="logo-img" alt="Logo"
                        style="width:60px; height:auto;">
                    <div class="brand-title">
                        SPRINTZONE
                    </div>
                </div>
                <!-- KANAN -->
                <div class="website">
                    SprintZone.online
                </div>
            </div>
            <!-- ADDRESS -->
            <div class="brand-address" style="margin-top:12px;">
                Jl. Inspeksi Kalimalang No.9, Cibatu, Cikarang Sel.,<br>
                Kabupaten Bekasi, Jawa Barat 17530<br>
                Telp: 0882-1353-4744
            </div>
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-section">
        <h1 class="report-title">Laporan User</h1>
        <p class="report-date">{{ $tanggal }}</p>
    </div>

    <!-- STATS -->
    <div class="stats-summary">
        <span><strong>Total User:</strong> {{ $totalUser }}</span>
        <span><strong>Total Order:</strong> {{ $totalOrder }}</span>
        <span><strong>Total Belanja:</strong> Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama</th>
                <th width="25%">Email</th>
                <th width="15%" class="text-right">Total Order</th>
                <th width="20%" class="text-right">Total Belanja</th>
                <th width="15%" class="text-right">Terakhir Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->orders_count }}</td>
                    <td class="text-right">Rp {{ number_format($user->orders_sum_total_price ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $user->orders()->latest('created_at')->first()?->created_at->format('d/m/Y') ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }} • Sistem Management SprintZone
    </div>

</body>

</html>
