@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Iuran</h6>
                            <h2 class="mb-0 text-white">Rp {{ number_format($iuran, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-cash-coin fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pengguna Aktif</h6>
                            <h2 class="mb-0 text-white">{{ $activeUsers }}</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-person-check-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Nominal Pinjaman</h6>
                            <h2 class="mb-0 text-white">Rp {{ number_format($totalAmountLoans, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon">
                            <i class="bi bi-cash-coin fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Statistik Pinjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-warning text-dark mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Qardh</h6>
                                    <h3 class="mb-0">{{ $qardhLoans }}</h3>
                                    <small>Pengguna</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-danger text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Bisnis</h6>
                                    <h3 class="mb-0">{{ $bisnisLoans }}</h3>
                                    <small>Pengguna</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="loanChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Statistik Nominal Simpanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-secondary text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total 'Amount'</h6>
                                    <h3 class="mb-0">Rp {{ number_format($totalAmountSavings, 0, ',', '.') }}</h3>
                                    <small>Dari Kolom Amount</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total 'Wajib'</h6>
                                    <h3 class="mb-0">Rp {{ number_format($totalWajibAmountSavings, 0, ',', '.') }}</h3>
                                    <small>Dari Kolom Wajib Amount</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total 'Sukarela'</h6>
                                    <h3 class="mb-0">Rp {{ number_format($totalSukarelaAmountSavings, 0, ',', '.') }}</h3>
                                    <small>Dari Kolom Sukarela Amount</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="savingChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const loanCtx = document.getElementById('loanChart').getContext('2d');
        const loanChart = new Chart(loanCtx, {
            type: 'doughnut',
            data: {
                labels: ['Qardh', 'Bisnis'],
                datasets: [{
                    data: [{{ $loanChartData['qardh'] }}, {{ $loanChartData['bisnis'] }}],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        const savingCtx = document.getElementById('savingChart').getContext('2d');
        const savingChart = new Chart(savingCtx, {
            type: 'doughnut',
            data: {
                // Label untuk chart disesuaikan dengan nama kolom
                labels: ['Total Amount', 'Total Wajib Amount', 'Total Sukarela Amount'],
                datasets: [{
                    // Data untuk chart diambil dari variabel baru
                    data: [
                        {{ $savingChartData['amount'] }},
                        {{ $savingChartData['wajib_amount'] }},
                        {{ $savingChartData['sukarela_amount'] }}
                    ],
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.8)', // Warna bisa disesuaikan
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endpush
