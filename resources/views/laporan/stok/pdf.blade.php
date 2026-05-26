{<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 20px; }
        
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px solid #FF4500; 
            padding-bottom: 15px; 
        }
        .header h1 { 
            font-size: 22px; 
            font-weight: bold; 
            color: #FF4500; 
            text-transform: uppercase; 
            letter-spacing: 2px;
        }
        .header p { color: #666; margin-top: 5px; }
        
        .stats { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            gap: 10px;
        }
        .stat-box { 
            flex: 1; 
            background: linear-gradient(135deg, #FF4500, #ff6b35); 
            color: white; 
            padding: 12px; 
            border-radius: 8px; 
            text-align: center;
        }
        .stat-box p { font-size: 9px; text-transform: uppercase; opacity: 0.8; }
        .stat-box .value { font-size: 18px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { 
            background: #333; 
            color: white; 
            padding: 8px; 
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase; 
        }
        td { padding: 7px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        
        .low-stock { color: #ff6b35; font-weight: bold; }
        .out-stock { color: red; font-weight: bold; }
        .ready { color: green; font-weight: bold; }
        
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #999; 
            font-size: 9px; 
            border-top: 1px solid #ddd; 
            padding-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Stok Produk</h1>
        <p>SprintZone Store • {{ $tanggal }}</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <p>Total Variant</p>
            <div class="value">{{ $variants->count() }}</div>
        </div>
        <div class="stat-box">
            <p>Total Stok</p>
            <div class="value">{{ number_format($totalStock) }}</div>
        </div>
        <div class="stat-box">
            <p>Low Stock</p>
            <div class="value">{{ $variants->filter(fn($v) => $v->stock > 0 && $v->stock < 5)->count() }}</div>
        </div>
        <div class="stat-box">
            <p>Habis</p>
            <div class="value">{{ $variants->filter(fn($v) => $v->stock == 0)->count() }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th>Warna</th>
                <th>Size</th>
                <th>SKU</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variants as $index => $v)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $v->product->name ?? '-' }}</td>
                    <td>{{ $v->product->category->name ?? '-' }}</td>
                    <td>{{ $v->product->brand->name ?? '-' }}</td>
                    <td>{{ $v->color ?? '-' }}</td>
                    <td>{{ $v->size ?? '-' }}</td>
                    <td>{{ $v->sku ?? '-' }}</td>
                    <td>Rp {{ number_format($v->price, 0, ',', '.') }}</td>
                    <td class="{{ $v->stock == 0 ? 'out-stock' : ($v->stock < 5 ? 'low-stock' : '') }}">
                        {{ $v->stock }}
                    </td>
                    <td class="{{ $v->stock == 0 ? 'out-stock' : ($v->stock < 5 ? 'low-stock' : 'ready') }}">
                        @if($v->stock == 0) Habis
                        @elseif($v->stock < 5) Low Stock
                        @else Ready @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada {{ now()->format('d F Y H:i:s') }} • Sistem Management SprintZone</p>
    </div>
</body>
</html>