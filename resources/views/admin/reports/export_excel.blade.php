<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-size: 11pt;
            text-align: center;
            color: #4b5563;
        }
        .meta-table {
            margin-bottom: 20px;
        }
        .meta-label {
            font-weight: bold;
            width: 150px;
        }
        .summary-card {
            background-color: #f3f4f6;
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: center;
        }
        .summary-title {
            font-size: 9pt;
            color: #6b7280;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .data-table th {
            background-color: #e85824;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: middle;
        }
        .zebra {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table>
        <tr>
            <td colspan="8" class="title">LAPORAN KEUANGAN SAJIHUB</td>
        </tr>
        <tr>
            <td colspan="8" class="subtitle">Cabang: {{ $branch->name }}</td>
        </tr>
        <tr>
            <td colspan="8" class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDateInput)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDateInput)->translatedFormat('d F Y') }} ({{ ucfirst($preset) }})</td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
    </table>

    <!-- Ringkasan Laporan -->
    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2" style="background-color: #1f2937; color: white;">METRIK UTAMA</th>
                <th colspan="2" style="background-color: #1f2937; color: white;">METODE PEMBAYARAN</th>
                <th colspan="4" style="background-color: #1f2937; color: white;">NILAI PENDAPATAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Total Pendapatan</td>
                <td class="font-bold text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td>Tunai (Cash)</td>
                <td class="text-right">Rp {{ number_format($paymentMethods->get('cash')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('cash')['count'] ?? 0 }} pesanan)</td>
                <td colspan="4" rowspan="3" style="vertical-align: middle; text-align: center; font-size: 14pt; background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;" class="font-bold">
                    Omset Terkumpul:<br>
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Jumlah Pesanan Selesai</td>
                <td class="text-right">{{ $totalOrders }} transaksi</td>
                <td>QRIS</td>
                <td class="text-right">Rp {{ number_format($paymentMethods->get('qris')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('qris')['count'] ?? 0 }} pesanan)</td>
            </tr>
            <tr>
                <td>Rata-Rata Transaksi</td>
                <td class="text-right">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</td>
                <td>Transfer Bank</td>
                <td class="text-right">Rp {{ number_format($paymentMethods->get('transfer')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('transfer')['count'] ?? 0 }} pesanan)</td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- Rincian Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;" class="text-center">No</th>
                <th style="width: 120px;" class="text-center">ID Pesanan</th>
                <th style="width: 180px;">Tanggal / Jam</th>
                <th style="width: 180px;">Nama Pelanggan</th>
                <th style="width: 100px;" class="text-center">Nomor Meja</th>
                <th style="width: 140px;">Metode Pembayaran</th>
                <th style="width: 180px;">Kasir Penanggung Jawab</th>
                <th style="width: 150px;" class="text-right">Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($ordersList ?? [] as $order)
            <tr class="{{ $no % 2 == 0 ? 'zebra' : '' }}">
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center font-bold">#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                <td>{{ $order->customer_name }}</td>
                <td class="text-center">Meja {{ $order->table->table_number ?? '-' }}</td>
                <td>
                    @if($order->payment_method == 'cash')
                        Tunai
                    @elseif($order->payment_method == 'qris')
                        QRIS
                    @else
                        Transfer
                    @endif
                </td>
                <td>{{ $order->user->name ?? 'Scan QR / Sistem' }}</td>
                <td class="text-right font-bold" style="color: #e85824;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #6b7280; padding: 20px;">Tidak ada transaksi ditemukan pada periode terpilih.</td>
            </tr>
            @endforelse
            @if($ordersList->isNotEmpty())
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="7" class="text-right" style="padding: 10px;">TOTAL OMSET KESELURUHAN</td>
                <td class="text-right text-brand-500" style="color: #e85824; padding: 10px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
