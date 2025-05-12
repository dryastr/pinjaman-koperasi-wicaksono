@extends('layouts.main')

@section('title', 'Dashboard Supervisor')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Menunggu Approval</h6>
                            <h2 class="mb-0">{{ $pendingCount }}</h2>
                            <small>Anggota Baru</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-people fs-1"></i>
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
                            <h6 class="card-title">Disetujui Hari Ini</h6>
                            <h2 class="mb-0">{{ $approvedToday }}</h2>
                            <small>Anggota</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-check-circle fs-1"></i>
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
                            <h6 class="card-title">Total Anggota</h6>
                            <h2 class="mb-0">{{ $totalMembers }}</h2>
                            <small>Aktif</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-person-check fs-1"></i>
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
                    <h5 class="card-title">Aktivitas Approval 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="approvalChart" height="150"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row" id="pendingUsers">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Anggota Baru Butuh Approval</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($pendingUsers->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada anggota baru yang perlu disetujui
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Dokumen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingUsers as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if ($user->borrowerProfile)
                                                        <a href="{{ asset('storage/' . $user->borrowerProfile->foto_ktp) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">KTP</a>
                                                        <a href="{{ asset('storage/' . $user->borrowerProfile->foto_kk) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">KK</a>
                                                    @else
                                                        <span class="badge bg-danger">Belum lengkap</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('approvalChart').getContext('2d');
        const approvalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($approvalDates),
                datasets: [{
                    label: 'Anggota Disetujui',
                    data: @json($approvalData),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
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
                }
            }
        });
    </script>
@endpush
