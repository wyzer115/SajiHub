<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Standard thermal printer width */
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px 0;
            font-size: 11px;
        }
        .info-table td:last-child {
            text-align: right;
        }
        .items-table th {
            border-bottom: 1px dashed #000;
            text-align: left;
            font-size: 11px;
            padding-bottom: 4px;
        }
        .items-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .items-table .qty {
            text-align: center;
            width: 10%;
        }
        .items-table .price {
            text-align: right;
            width: 30%;
        }
        .items-table .total {
            text-align: right;
            width: 30%;
        }
        .notes {
            font-size: 9px;
            font-style: italic;
            padding-left: 15px;
            margin-top: -2px;
            margin-bottom: 4px;
        }
        .totals {
            margin-top: 10px;
            font-size: 12px;
            font-weight: bold;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body {
                width: 100%;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="no-print" style="margin-bottom: 15px; text-align: center;">
        <button onclick="window.print();" style="padding: 6px 12px; background: #e85824; border: none; color: #fff; font-weight: bold; cursor: pointer; border-radius: 4px;">Cetak Struk</button>
        <button onclick="window.close();" style="padding: 6px 12px; background: #72767d; border: none; color: #fff; font-weight: bold; cursor: pointer; border-radius: 4px; margin-left: 5px;">Tutup Halaman</button>
        <div class="separator"></div>
    </div>

    <div class="header">
        <h2>SajiHUB</h2>
        <h3>{{ $order->branch->name ?? 'Cabang Restoran' }}</h3>
        <p>{{ $order->branch->address ?? '' }}</p>
        @if($order->branch->phone)
            <p>Telp: {{ $order->branch->phone }}</p>
        @endif
    </div>

    <div class="separator"></div>

    <table class="info-table">
        <tr>
            <td>No. Pesanan: #{{ $order->id }}</td>
            <td>Meja: {{ $order->table->table_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>Pelanggan: {{ $order->customer_name }}</td>
        </tr>
        <tr>
            <td>Kasir: {{ $order->user->name ?? 'Sistem' }}</td>
            <td>Metode: {{ strtoupper($order->payment_method ?? 'cash') }}</td>
        </tr>
    </table>

    <div class="separator"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Menu</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td class="price">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="total">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @if($item->notes)
                    <tr>
                        <td colspan="4" class="notes">* {{ $item->notes }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>

    <div class="totals">
        <div class="totals-row">
            <span>TOTAL BELANJA:</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="totals-row" style="font-size: 10px; font-weight: normal; margin-top: 5px;">
            <span>STATUS BAYAR:</span>
            <span>{{ strtoupper($order->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar') }}</span>
        </div>
    </div>

    <div class="separator"></div>

    <div class="footer">
        <p>Terima Kasih atas Kunjungan Anda!</p>
        <p>SajiHUB Restaurant Systems</p>
    </div>

</body>
</html>
