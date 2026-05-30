<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Produk</title>
    <style>
        @page {
            margin: 20mm 15mm 15mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            padding: 20px;
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
            color: #000;
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
            margin: 0 10px;
        }

        .stats-summary .divider {
            font-weight: bold;
            color: #000;
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

        /* STATUS */
        .status-habis {
            font-weight: bold;
        }

        .status-low {
            font-weight: bold;
        }

        .status-ready {
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
        <h1 class="report-title">Laporan Stok Produk</h1>
        <p class="report-date">{{ $tanggal }}</p>
    </div>

    <!-- STATS -->
    <div class="stats-summary">
        <span><strong>Total Variant:</strong> {{ $variants->count() }}</span>
        <span class="divider">|</span>
        <span><strong>Total Stok:</strong> {{ number_format($totalStock) }}</span>
        <span class="divider">|</span>
        <span><strong>Low Stock:</strong>
            {{ $variants->filter(fn($v) => $v->stock > 0 && $v->stock < 10)->count() }}</span>
        <span class="divider">|</span>
        <span><strong>Habis:</strong> {{ $variants->filter(fn($v) => $v->stock == 0)->count() }}</span>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Produk</th>
                <th width="12%">Kategori</th>
                <th width="10%">Brand</th>
                <th width="8%">Warna</th>
                <th width="5%">Size</th>
                <th width="12%">SKU</th>
                <th width="12%">Harga</th>
                <th width="8%">Stok</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($variants as $index => $v)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $v->product->name ?? '-' }}</td>
                    <td>{{ $v->product->category->name ?? '-' }}</td>
                    <td>{{ $v->product->brand->name ?? '-' }}</td>
                    <td>{{ $v->color ?? '-' }}</td>
                    <td class="text-center">{{ $v->size ?? '-' }}</td>
                    <td>{{ $v->sku ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($v->price, 0, ',', '.') }}</td>
                    <td class="text-center {{ $v->stock == 0 ? 'out-stock' : ($v->stock < 5 ? 'low-stock' : '') }}">
                        {{ $v->stock }}
                    </td>
                    <td class="text-center">
                        @if ($v->stock == 0)
                            <span class="status-habis">HABIS</span>
                        @elseif($v->stock < 10)
                            <span class="status-low">LOW</span>
                        @else
                            <span class="status-ready">READY</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }} • Sistem Management SprintZone
    </div>

</body>

</html>
