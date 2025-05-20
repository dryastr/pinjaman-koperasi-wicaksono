@extends('layouts.main')

@section('title', 'Manajemen Simpanan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Simpanan Anggota</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createSavingModal">
                            Tambah Simpanan
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <th>Wajib (Rp)</th>
                                        <th>Sukarela (Rp)</th>
                                        <th>Total (Rp)</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($savings as $saving)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $saving->user->name }}</td>
                                            <td>Rp {{ number_format($saving->wajib_amount, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($saving->sukarela_amount, 0, ',', '.') }}</td>
                                            <td>Rp
                                                {{ number_format($saving->wajib_amount + $saving->sukarela_amount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $saving->date->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $saving->status == 'approved' ? 'success' : ($saving->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($saving->status) }}
                                                </span>
                                            </td>
                                            <td class="text-nowrap">
                                                @if ($saving->status == 'pending')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="openEditModal({{ $saving->id }}, {{ $saving->user_id }}, {{ $saving->wajib_amount }}, {{ $saving->sukarela_amount }}, '{{ $saving->date->format('Y-m-d') }}')">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="{{ route('savings.destroy', $saving->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus simpanan ini?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Tidak diizinkan</span>
                                                @endif
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

    <div class="modal fade" id="createSavingModal" tabindex="-1" aria-labelledby="createSavingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSavingModalLabel">Tambah Simpanan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createSavingForm" method="POST" action="{{ route('savings.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createUserId" class="form-label">Anggota</label>
                            <select class="form-select" id="createUserId" name="user_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createWajibAmount" class="form-label">Jumlah Wajib (Rp)</label>
                            <input type="number" class="form-control" id="createWajibAmount" name="wajib_amount"
                                min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="createSukarelaAmount" class="form-label">Jumlah Sukarela (Rp)</label>
                            <input type="number" class="form-control" id="createSukarelaAmount" name="sukarela_amount"
                                min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="createDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="createDate" name="date" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSavingModal" tabindex="-1" aria-labelledby="editSavingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSavingModalLabel">Edit Simpanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSavingForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editSavingId" name="id">
                        <div class="mb-3">
                            <label for="editUserId" class="form-label">Anggota</label>
                            <select class="form-select" id="editUserId" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editWajibAmount" class="form-label">Jumlah Wajib (Rp)</label>
                            <input type="number" class="form-control" id="editWajibAmount" name="wajib_amount"
                                min="0">
                        </div>
                        <div class="mb-3">
                            <label for="editSukarelaAmount" class="form-label">Jumlah Sukarela (Rp)</label>
                            <input type="number" class="form-control" id="editSukarelaAmount" name="sukarela_amount"
                                min="0">
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, userId, wajibAmount, sukarelaAmount, date) {
            document.getElementById('editSavingId').value = id;
            document.getElementById('editUserId').value = userId;
            document.getElementById('editWajibAmount').value = wajibAmount;
            document.getElementById('editSukarelaAmount').value = sukarelaAmount;
            document.getElementById('editDate').value = date;

            document.getElementById('editSavingForm').action = '{{ route('savings.update', '') }}/' + id;

            var editModal = new bootstrap.Modal(document.getElementById('editSavingModal'));
            editModal.show();
        }

        document.getElementById('createDate').valueAsDate = new Date();
    </script>
@endsection
