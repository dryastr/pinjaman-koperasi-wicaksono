@extends('layouts.main')

@section('title', 'Dashboard User')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Tabungan</h6>
                            <h2 class="mb-0">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-piggy-bank fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Jumlah Menabung</h6>
                            <h2 class="mb-0">{{ $savingCount }}x</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-cash-stack fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Pinjaman</h6>
                            <h2 class="mb-0">Rp {{ number_format($loanAmount, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-currency-exchange fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Sisa Durasi Pinjaman</h6>
                            <h2 class="mb-0">{{ $remainingDuration }} Minggu</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-calendar-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($loanAmount > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Pinjaman</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3">
                        @php
                            $progressPercentage = $loanAmount > 0 ? ($totalPaid / $loanAmount) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $progressPercentage }}%"
                             aria-valuenow="{{ $progressPercentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ round($progressPercentage, 2) }}%
                        </div>
                    </div>
                    <p class="mb-1">Total yang sudah dibayar: Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                    <p class="mb-0">Sisa pinjaman: Rp {{ number_format($loanAmount - $totalPaid, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
