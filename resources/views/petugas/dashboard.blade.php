@extends('layouts.main')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Tabungan Hari Ini</h6>
                            <h2 class="mb-0">{{ $todaySavings }}</h2>
                            <small>Transaksi</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-cash-coin fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pembayaran Pinjaman Hari Ini</h6>
                            <h2 class="mb-0">{{ $todayLoanPayments }}</h2>
                            <small>Transaksi</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-currency-exchange fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Aktivitas Transaksi 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="transactionChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Statistik Penting</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <span>Total Tabungan</span>
                            <strong>{{ $totalSavings }}</strong>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <div class="d-flex justify-content-between">
                            <span>Total Pembayaran Pinjaman</span>
                            <strong>{{ $totalLoanPayments }}</strong>
                        </div>
                    </div>
                    <div class="alert alert-danger">
                        <div class="d-flex justify-content-between">
                            <span>Pinjaman Terlambat</span>
                            <strong>{{ $overdueLoans }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dates),
                datasets: [
                    {
                        label: 'Tabungan',
                        data: @json($savingData),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pembayaran Pinjaman',
                        data: @json($paymentData),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
@endpush
