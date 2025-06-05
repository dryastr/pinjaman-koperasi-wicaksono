@extends('layouts.main')

@section('title', 'Pengajuan Pinjaman')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-start justify-content-between">

                        <div class="d-flex flex-column gap-2 align-items-start">
                            <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                            @if (!empty($warnings))
                                <div class="alert alert-warning mt-3 w-100">
                                    <ul class="mb-0">
                                        @foreach ($warnings as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 align-items-center justify-content-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#createLoanModal">
                                Buat Pengajuan Baru
                            </button>
                            <a href="{{ route('loan-applications.print') }}" target="_blank"
                                class="btn btn-outline-primary">
                                Print Data Pinjaman Aktif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pinjaman</th>
                                        <th>Jumlah Pinjaman</th>
                                        <th>Durasi (Minggu)</th>
                                        <th>Sisa Durasi Pembayaran</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $loan->jenis_pinjaman }}</td>
                                            <td>Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($loan->status !== 'approved')
                                                    {{ $loan->sisa_durasi_pinjaman + 1 }} Minggu
                                                @else
                                                    {{ $loan->sisa_durasi_pinjaman }} Minggu
                                                @endif
                                            </td>
                                            <td>
                                                @if ($loan->status !== 'approved')
                                                    {{ $loan->sisa_durasi_pinjaman + 1 }} Minggu
                                                @elseif ($loan->sisa_durasi_pinjaman == 0)
                                                    <label for="" class="badge bg-success">Lunas</label>
                                                @else
                                                    {{ $loan->sisa_durasi_pinjaman }} Minggu
                                                @endif
                                            </td>
                                            <td>{{ $loan->created_at->format('d F Y') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $loan->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $loan->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="showDetailModal({{ json_encode($loan) }})">
                                                                Detail
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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

    <!-- Create Modal -->
    <div class="modal fade" id="createLoanModal" tabindex="-1" aria-labelledby="createLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLoanModalLabel">Buat Pengajuan Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createLoanForm" method="POST" action="{{ route('loan-applications.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createJenisPinjaman" class="form-label">Jenis Pinjaman</label>
                            <select class="form-select" id="createJenisPinjaman" name="jenis_pinjaman" required>
                                <option value="">Pilih Jenis Pinjaman</option>
                                <option value="qardh">Qardh</option>
                                <option value="bisnis">Bisnis</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createJumlahPinjaman" class="form-label">Jumlah Pinjaman (Rp)</label>
                            <input type="number" class="form-control" id="createJumlahPinjaman" name="jumlah_pinjaman"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="createDurasiBulan" class="form-label">Durasi (Minggu)</label>
                            <select class="form-select" id="createDurasiBulan" name="durasi_bulan" required>
                                <option value="">Pilih Durasi</option>
                                <option value="30">30 Minggu</option>
                                <option value="40">40 Minggu</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailLoanModal" tabindex="-1" aria-labelledby="detailLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailLoanModalLabel">Detail Pengajuan Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Pinjaman</label>
                        <p id="detailJenisPinjaman" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Pinjaman</label>
                        <p id="detailJumlahPinjaman" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Durasi</label>
                        <p id="detailDurasiBulan" class="form-control-plaintext"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(loan) {
            document.getElementById('detailJenisPinjaman').textContent = loan.jenis_pinjaman;
            document.getElementById('detailJumlahPinjaman').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(loan
                .jumlah_pinjaman);
            document.getElementById('detailDurasiBulan').textContent = loan.durasi_bulan + ' bulan';

            var detailModal = new bootstrap.Modal(document.getElementById('detailLoanModal'));
            detailModal.show();
        }
    </script>
@endsection
