<!DOCTYPE html>
<html>

<head>
    <title>Cetak Data Pinjaman Aktif</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .loan-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 25px;
            page-break-inside: avoid;
            background: #f9f9f9;
        }

        .section-title {
            font-weight: bold;
            margin: 15px 0 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
            color: #444;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 20px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-item strong {
            display: inline-block;
            width: 120px;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        .payment-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .no-payment {
            font-style: italic;
            color: #777;
            padding: 10px 0;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            body {
                padding: 0;
            }

            .loan-card {
                border: 1px solid #ccc;
                background: white;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DATA PINJAMAN AKTIF</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    @foreach ($loans as $loan)
        <div class="loan-card">
            <div class="section-title">INFORMASI PINJAMAN</div>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Jenis Pinjaman:</strong> {{ $loan->jenis_pinjaman }}
                </div>
                <div class="info-item">
                    <strong>Jumlah Pinjaman:</strong> Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }}
                </div>
                <div class="info-item">
                    <strong>Durasi:</strong> {{ $loan->durasi_bulan }} minggu
                </div>
                <div class="info-item">
                    <strong>Sisa Durasi:</strong> {{ $loan->sisa_durasi_pinjaman }} minggu
                </div>
                <div class="info-item">
                    <strong>Status:</strong> <span style="text-transform: capitalize;">{{ $loan->status }}</span>
                </div>
                <div class="info-item">
                    <strong>Tanggal Pengajuan:</strong> {{ $loan->created_at->format('d-m-Y') }}
                </div>
            </div>

            <div class="section-title">INFORMASI PEMINJAM</div>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama:</strong> {{ $loan->user->name }}
                </div>
                <div class="info-item">
                    <strong>Email:</strong> {{ $loan->user->email }}
                </div>
            </div>

            <div class="section-title">RIWAYAT PEMBAYARAN</div>
            @if ($loan->payments->count() > 0)
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Jumlah Dibayar</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->payments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment->tanggal_pembayaran->format('d-m-Y') }}</td>
                                <td>Rp {{ number_format($payment->jumlah_dibayar, 0, ',', '.') }}</td>
                                <td>{{ $payment->metode_pembayaran }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-payment">Belum ada pembayaran.</p>
            @endif
        </div>
    @endforeach

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh sistem
    </div>
</body>

</html>
