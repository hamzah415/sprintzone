<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian - SprintZone</title>

    <style>
        @page {
            margin: 20mm 15mm 15mm 15mm;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .page{
            width: 100%;
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
            font-size: 24px;
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
            margin-bottom: 5px;
        }

        .website {
            font-size: 10px;
            font-weight: bold;
        }

        /* TITLE */
        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .report-date {
            font-size: 12px;
            margin: 0;
        }

        /* TOTAL BOX */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .income-box {
            width: 250px;
            border: 1px solid #000;
        }

        .income-header {
            background-color: #000;
            color: #fff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .income-content {
            padding: 10px;
            text-align: right;
            font-size: 14px;
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
            font-size: 10px;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #000;
            text-transform: uppercase;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        tbody td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

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

<div class="page">

    <!-- HEADER -->
    <div class="header-container">
        <div>
            <div class="brand-title">SPRINTZONE</div>
            <div class="brand-address">
                Jl. Inspeksi Kalimalang No.9, Cibatu, Cikarang Sel.,<br>
                Kabupaten Bekasi, Jawa Barat 17530<br>
                Telp: 0882-1353-4744
            </div>
        </div>
        
        <div class="text-right">
            <img src="{{ public_path('img/logo.png') }}" class="logo-img" alt="Logo">
            <div class="website">SprintZone.online</div>
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-section">
        <h1 class="report-title">Laporan Penjualan</h1>
        <p class="report-date">{{ $tanggal }}</p>
    </div>

    <!-- TOTAL PENDAPATAN -->
    <div class="summary-wrapper">
        <div class="income-box">
            <div class="income-header">Total Pendapatan</div>
            <div class="income-content">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="8%">ID</th>
                <th width="18%">Customer</th>
                <th width="30%">Produk</th>
                <th width="5%" class="text-center">Qty</th>
                <th width="10%">Status</th>
                <th width="12%" class="text-right">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->customer_name ?? 'Guest' }}</td>
                
                <!-- Produk Items -->
                <td>
                    @forelse($order->items as $item)
                        <div style="margin-bottom: 2px;">
                            {{ $item->variant->product->name ?? '-' }}
                            @if($item->variant?->size)
                                ({{ $item->variant->size }})
                            @endif
                        </div>
                    @empty
                        -
                    @endforelse
                </td>

                <!-- Qty Items -->
                <td class="text-center">
                    @forelse($order->items as $item)
                        {{ $item->qty }}<br>
                    @empty
                        -
                    @endforelse
                </td>

                <!-- Status -->
                <td>{{ ucfirst($order->status) }}</td>

                <!-- Total Price -->
                <td class="text-right">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }} | Sistem Management SprintZone
    </div>

</div>

</body>
</html>