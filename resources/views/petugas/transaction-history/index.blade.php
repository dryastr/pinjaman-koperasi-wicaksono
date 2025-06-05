@extends('layouts.main')

@section('title', 'Riwayat Transaksi Pengguna')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Riwayat Transaksi Pengguna</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengguna</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $transaction->user->name }}</td>
                                            <td>
                                                @if ($transaction instanceof \App\Models\LoanPayment)
                                                    Pembayaran Pinjaman
                                                @else
                                                    @if (is_null($transaction->type))
                                                        Tabungan (Wajib dan Sukarela)
                                                    @else
                                                        Tabungan ({{ ucfirst($transaction->type) }})
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->amount == 0)
                                                    Wajib: Rp {{ number_format($transaction->wajib_amount, 0, ',', '.') }}<br>
                                                    Sukarela: Rp {{ number_format($transaction->sukarela_amount, 0, ',', '.') }}
                                                @else
                                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction instanceof \App\Models\LoanPayment)
                                                    Pinjaman #{{ $transaction->loan_id }}
                                                @else
                                                    {{ ucfirst($transaction->type) }}
                                                @endif
                                            </td>
                                            <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $transaction->status == 'approved' ? 'success' : ($transaction->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
