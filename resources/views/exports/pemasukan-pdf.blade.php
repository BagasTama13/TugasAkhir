<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{-- Title dokumen PDF --}}
    <title>Laporan Pemasukan - {{ $monthName }}</title>
    {{-- Inline CSS digunakan karena DomPDF (library pembuat PDF) lebih optimal membaca CSS secara inline/internal --}}
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1e40af;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #1e293b;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row th, .total-row td {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .signature-space {
            height: 80px;
        }
    </style>
</head>
<body>
    {{-- Bagian Kop Surat / Header Laporan --}}
    <div class="header">
        <h1>LAPORAN PEMASUKAN BPTRANS</h1>
        <p>Periode: {{ $monthName }}</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    {{-- Tabel Data Pemasukan --}}
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="35%">Keterangan</th>
                <th width="15%">Kategori</th>
                <th width="15%">Petugas</th>
                <th width="15%" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            {{-- Looping data pemasukan yang dikirim dari controller --}}
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ ucfirst($row->kategori) }}</td>
                    <td>{{ $row->user ? $row->user->name : '-' }}</td>
                    <td class="text-right">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                {{-- Ditampilkan jika tidak ada data pemasukan pada bulan tersebut --}}
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pemasukan pada periode ini.</td>
                </tr>
            @endforelse
            {{-- Baris Total --}}
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pendapatan</td>
                <td class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Bagian Tanda Tangan --}}
    <div class="footer">
        <p>Mengetahui,</p>
        <div class="signature-space"></div>
        <p><strong>Administrator</strong></p>
    </div>
</body>
</html>
