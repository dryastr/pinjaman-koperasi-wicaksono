@extends('layouts.main')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Pengajuan Pinjaman</h4>
                    <a href="{{ route('pengajuan-pinjaman.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>Informasi Pengaju</h5>
                                    <hr>
                                    <p><strong>Nama:</strong> {{ $application->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $application->user->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>Detail Pinjaman</h5>
                                    <hr>
                                    <p><strong>Jenis Pinjaman:</strong> {{ $application->jenis_pinjaman }}</p>
                                    <p><strong>Jumlah Pinjaman:</strong> Rp
                                        {{ number_format($application->jumlah_pinjaman, 0, ',', '.') }}</p>
                                    <p><strong>Durasi:</strong> {{ $application->durasi_bulan }} bulan</p>
                                    <p><strong>Sisa Durasi:</strong> {{ $application->sisa_durasi_pinjaman }} bulan</p>
                                    <p><strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $application->status == 'accepted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </p>
                                    @if ($application->petugas)
                                        <p><strong>Petugas Penangan:</strong> {{ $application->petugas->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
