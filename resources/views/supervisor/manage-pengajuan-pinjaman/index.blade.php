@extends('layouts.main')

@section('title', 'Kelola Pengajuan Pinjaman')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 25px!important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengaju</th>
                                        <th>Jenis Pinjaman</th>
                                        <th>Jumlah</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Petugas</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingApplications as $application)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $application->user->name }}</td>
                                            <td>{{ $application->jenis_pinjaman }}</td>
                                            <td>Rp {{ number_format($application->jumlah_pinjaman, 0, ',', '.') }}</td>
                                            <td>{{ $application->durasi_bulan }} bulan</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $application->status == 'accepted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $application->petugas ? $application->petugas->name : '-' }}</td>
                                            <td>{{ $application->created_at->format('d M Y') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $application->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $application->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $application->id }}, '{{ $application->status }}', {{ $application->petugas_id ?? 'null' }})">
                                                                Edit Pengajuan
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('pengajuan-pinjaman.show', $application->id) }}">
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

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pengajuan Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status">
                                <option value="">-- Pilih Status (opsional) --</option>
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editPetugas" class="form-label">Petugas Penangan</label>
                            <select class="form-select" id="editPetugas" name="petugas_id">
                                <option value="">-- Pilih Petugas (opsional) --</option>
                                @foreach ($petugasList as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, status, petugasId) {
            document.getElementById('editId').value = id;
            document.getElementById('editForm').action = '{{ route('pengajuan-pinjaman.update', '') }}/' + id;

            if (status) {
                document.getElementById('editStatus').value = status;
            }

            if (petugasId) {
                document.getElementById('editPetugas').value = petugasId;
            }

            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }
    </script>
@endsection
