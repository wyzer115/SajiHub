<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1f2937;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            color: #8C0000;
        }
        .subtitle {
            font-size: 11pt;
            text-align: center;
            color: #4b5563;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        .data-table th {
            background-color: #8C0000;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            font-size: 10pt;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            vertical-align: middle;
            font-size: 9.5pt;
        }
        .section-header th {
            background-color: #1f2937 !important;
            color: #ffffff !important;
            font-size: 10.5pt;
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
        .highlight-green {
            background-color: #ecfdf5;
            color: #065f46;
            font-weight: bold;
        }
        .highlight-red {
            background-color: #fef2f2;
            color: #991b1b;
            font-weight: bold;
        }
        .highlight-accent {
            background-color: #fff7ed;
            color: #9a3412;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen -->
    <table>
        <tr>
            <td colspan="7" class="title">LAPORAN LABA RUGI EKSEKUTIF (PROFIT & LOSS)</td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">SajiHub - Multi-Branch Culinary Enterprise</td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">Cabang: <strong>{{ $selectedBranch ? $selectedBranch->name : 'Semua Cabang (Laporan Konsolidasi Gabungan)' }}</strong></td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">Periode Laporan: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong> | Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
    </table>

    <!-- 1. Ringkasan Eksekutif P&L -->
    <table class="data-table">
        <tr class="section-header">
            <th colspan="7">1. RINGKASAN EKSEKUTIF LABA RUGI</th>
        </tr>
        <tr>
            <td colspan="2" class="font-bold">Total Pendapatan Kotor (Omset)</td>
            <td colspan="2" class="text-right font-bold highlight-green">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
            <td colspan="2">Total Transaksi Lunas</td>
            <td class="text-right font-bold">{{ number_format($totalOrders, 0, ',', '.') }} pesanan</td>
        </tr>
        <tr>
            <td colspan="2" class="font-bold">Total Beban & Biaya Operasional</td>
            <td colspan="2" class="text-right font-bold highlight-red">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
            <td colspan="2">Rata-rata Nilai Transaksi (AOV)</td>
            <td class="text-right font-bold">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="font-bold">Laba Bersih Operasional (Net Profit)</td>
            <td colspan="2" class="text-right font-bold {{ $netProfit >= 0 ? 'highlight-green' : 'highlight-red' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </td>
            <td colspan="2">Net Profit Margin (%)</td>
            <td class="text-right font-bold {{ $profitMargin >= 0 ? 'highlight-green' : 'highlight-red' }}">{{ $profitMargin }}%</td>
        </tr>
        <tr>
            <td colspan="2" class="font-bold">Rasio Beban terhadap Pendapatan</td>
            <td colspan="2" class="text-right font-bold highlight-accent">{{ $expenseRatio }}%</td>
            <td colspan="2">Jumlah Catatan Beban</td>
            <td class="text-right font-bold">{{ count($expenses) }} transaksi</td>
        </tr>
    </table>

    <!-- 2. Breakdown Metode Pembayaran -->
    <table class="data-table">
        <tr class="section-header">
            <th colspan="7">2. KOMPOSISI METODE PEMBAYARAN KONSUMEN</th>
        </tr>
        <tr style="background-color: #374151; color: white;">
            <th colspan="3">Metode Pembayaran</th>
            <th colspan="2" class="text-center">Jumlah Transaksi</th>
            <th colspan="2" class="text-right">Total Penerimaan (Rp)</th>
        </tr>
        @forelse($paymentMethods as $pmKey => $pmData)
        <tr>
            <td colspan="3" class="font-bold uppercase">{{ $pmKey === 'cash' ? 'Tunai (Cash Kasir)' : strtoupper($pmKey) }}</td>
            <td colspan="2" class="text-center">{{ $pmData['count'] }} transaksi ({{ $pmData['percentage'] }}%)</td>
            <td colspan="2" class="text-right font-bold">Rp {{ number_format($pmData['total'], 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada data pembayaran lunas.</td>
        </tr>
        @endforelse
    </table>

    <!-- 3. Performa Antar Cabang (Hanya jika konsolidasi Semua Cabang) -->
    @if(!$selectedBranch && $branchPerformances->isNotEmpty())
    <table class="data-table">
        <tr class="section-header">
            <th colspan="7">3. PERBANDINGAN PERFORMA ANTAR CABANG</th>
        </tr>
        <tr style="background-color: #374151; color: white;">
            <th colspan="2">Nama Cabang</th>
            <th class="text-center">Pesanan</th>
            <th class="text-right">Pendapatan Kotor</th>
            <th class="text-right">Beban Operasional</th>
            <th class="text-right">Laba Bersih</th>
            <th class="text-center">Margin %</th>
        </tr>
        @foreach($branchPerformances as $bp)
        <tr>
            <td colspan="2" class="font-bold">{{ $bp['branch']->name }}</td>
            <td class="text-center">{{ $bp['orders'] }}</td>
            <td class="text-right font-bold highlight-green">Rp {{ number_format($bp['revenue'], 0, ',', '.') }}</td>
            <td class="text-right font-bold highlight-red">Rp {{ number_format($bp['expense'], 0, ',', '.') }}</td>
            <td class="text-right font-bold {{ $bp['profit'] >= 0 ? 'highlight-green' : 'highlight-red' }}">Rp {{ number_format($bp['profit'], 0, ',', '.') }}</td>
            <td class="text-center font-bold">{{ $bp['margin'] }}%</td>
        </tr>
        @endforeach
    </table>
    @endif

    <!-- 4. Rincian Pengeluaran per Kategori -->
    <table class="data-table">
        <tr class="section-header">
            <th colspan="7">4. ALOKASI BEBAN PENGELUARAN PER KATEGORI</th>
        </tr>
        <tr style="background-color: #374151; color: white;">
            <th colspan="4">Kategori Biaya</th>
            <th colspan="3" class="text-right">Total Nominal Beban</th>
        </tr>
        @forelse($expensesByCategory as $cat => $sum)
        <tr>
            <td colspan="4" class="font-bold uppercase">{{ str_replace('_', ' ', $cat) }}</td>
            <td colspan="3" class="text-right font-bold highlight-red">Rp {{ number_format($sum, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada catatan pengeluaran pada periode ini.</td>
        </tr>
        @endforelse
    </table>

    <!-- 5. Daftar Transaksi Biaya Operasional Lengkap -->
    <table class="data-table">
        <tr class="section-header">
            <th colspan="7">5. RINCIAN BUKU TRANSAKSI BIAYA OPERASIONAL</th>
        </tr>
        <tr style="background-color: #374151; color: white;">
            <th style="width: 40px;" class="text-center">No</th>
            <th>Cabang</th>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th colspan="2">Keterangan / Catatan</th>
            <th class="text-right">Nominal</th>
        </tr>
        @php $no = 1; @endphp
        @forelse($expenses as $exp)
        <tr class="{{ $no % 2 == 0 ? 'zebra' : '' }}">
            <td class="text-center">{{ $no++ }}</td>
            <td class="font-bold">{{ $exp->branch->name ?? '-' }}</td>
            <td>{{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}</td>
            <td class="uppercase">{{ str_replace('_', ' ', $exp->category) }}</td>
            <td colspan="2">
                <strong>{{ $exp->title }}</strong>
                @if($exp->notes)
                    <br><small style="color: #6b7280;">Catatan: {{ $exp->notes }}</small>
                @endif
            </td>
            <td class="text-right font-bold" style="color: #b91c1c;">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada transaksi pengeluaran pada periode ini.</td>
        </tr>
        @endforelse
        @if($expenses->isNotEmpty())
        <tr style="background-color: #f3f4f6; font-weight: bold;">
            <td colspan="6" class="text-right" style="padding: 10px;">TOTAL BIAYA OPERASIONAL</td>
            <td class="text-right font-bold highlight-red" style="padding: 10px;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

</body>
</html>
