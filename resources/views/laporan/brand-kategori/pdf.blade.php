<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Brand & Kategori</title>
    <style>
        @page {
            margin: 20mm 15mm 15mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
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
            font-size: 10px;
            text-align: center;
        }

        .stats-summary span {
            display: inline-block;
            margin: 0 10px;
        }

        .stats-summary strong {
            font-weight: bold;
        }

        /* ACCORDION / BRAND TABLE */
        .brand-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .brand-header {
            background-color: #e5e5e5;
            padding: 8px 10px;
            border: 1px solid #000;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }

        .brand-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 12px;
            font-weight: bold;
        }

        .brand-total {
            text-align: right;
        }

        .category-table {
            width: 100%;
            border-collapse: collapse;
        }

        .category-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 6px;
            text-align: left;
            border: 1px solid #000;
        }

        .category-table td {
            padding: 6px;
            border: 1px solid #000;
            font-size: 10px;
        }

        .category-table .text-right {
            text-align: right;
        }

        .category-table .text-center {
            text-align: center;
        }

        .category-table tfoot td {
            background-color: #e5e5e5;
            font-weight: bold;
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

    <!-- HEADER -->
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
        <h1 class="report-title">Laporan Brand & Kategori</h1>
        <p class="report-date">{{ $tanggal }}</p>
    </div>

    <!-- STATS -->
    <div class="stats-summary">
        <span><strong>Total Brand:</strong> {{ $totalBrand }}</span>
        <span><strong>Total Order:</strong> {{ $totalOrders }}</span>
        <span><strong>Total Qty:</strong> {{ $totalQty }}</span>
        <span><strong>Pendapatan:</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
    </div>

    <!-- BRAND LIST -->
    @forelse($brands as $brand)
        <div class="brand-section">
            <div class="brand-header">
                <div class="brand-header-left">
                    @if ($brand->image)
                        <img src="{{ public_path('storage/' . $brand->image) }}" class="brand-logo">
                    @endif
                    <span class="brand-name">{{ $brand->name }}</span>
                </div>
                <div class="brand-total">
                    {{ $brand->total_orders }} Order | {{ $brand->total_qty }} Qty | Rp
                    {{ number_format($brand->total_pendapatan, 0, ',', '.') }}
                </div>
            </div>

            @if ($brand->categories->count() > 0)
                <table class="category-table">
                    <thead>
                        <tr>
                            <th width="40%">Kategori</th>
                            <th width="20%" class="text-center">Order</th>
                            <th width="20%" class="text-right">Qty</th>
                            <th width="20%" class="text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brand->categories as $cat)
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td class="text-center">{{ $cat->total_orders }}</td>
                                <td class="text-right">{{ $cat->total_qty }}</td>
                                <td class="text-right">Rp {{ number_format($cat->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>TOTAL</td>
                            <td class="text-center">{{ $brand->total_orders }}</td>
                            <td class="text-right">{{ $brand->total_qty }}</td>
                            <td class="text-right">Rp {{ number_format($brand->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div style="padding: 10px; text-align: center; border: 1px solid #000;">Tidak ada penjualan</div>
            @endif
        </div>
    @empty
        <div style="padding: 20px; text-align: center;">Tidak ada data</div>
    @endforelse

    <!-- FOOTER -->
    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }} • Sistem Management SprintZone
    </div>

</body>

</html>
