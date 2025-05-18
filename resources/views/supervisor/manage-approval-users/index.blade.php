@extends('layouts.main')

@section('title', 'Kelola Persetujuan User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar User Menunggu Persetujuan</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 25px!important">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->user->name }}</td>
                                            <td>{{ $user->user->email }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->status == 'accepted' ? 'success' : ($user->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $user->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $user->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick='showDetailModal(@json($user))'>
                                                                Lihat Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $user->id }}, '{{ $user->status }}')">
                                                                Ubah Status
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-approval-users.destroy', $user->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">
                                                                    Hapus
                                                                </button>
                                                            </form>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Ubah Status User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Profil -->
    <div class="modal fade" id="detailProfileModal" tabindex="-1" aria-labelledby="detailProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Profil Peminjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <strong>Nama:</strong>
                        <div id="detail-nama" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Alamat:</strong>
                        <div id="detail-alamat" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Tempat Lahir:</strong>
                        <div id="detail-tempat-lahir" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Lahir:</strong>
                        <div id="detail-tanggal-lahir" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Pekerjaan:</strong>
                        <div id="detail-pekerjaan" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Jenis Simpanan:</strong>
                        <div id="detail-jenis-simpanan" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Tabungan Pokok:</strong>
                        <div id="detail-tabungan-pokok" class="text-muted"></div>
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <div id="detail-status" class="text-muted"></div>
                    </div>
                    <div class="mb-3">
                        <strong>Foto KTP:</strong><br>
                        <img id="detail-ktp" src="" class="img-fluid rounded" width="200" />
                    </div>
                    <div class="mb-3">
                        <strong>Foto KK:</strong><br>
                        <img id="detail-kk" src="" class="img-fluid rounded" width="200" />
                    </div>
                    <div class="mb-3">
                        <strong>Foto Diri:</strong><br>
                        <img id="detail-diri" src="" class="img-fluid rounded" width="200" />
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function openEditModal(id, status) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editStatus').value = status;

            document.getElementById('editUserForm').action = '{{ route('manage-approval-users.update', '') }}/' + id;

            var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }
    </script>
    <script>
        function showDetailModal(profile) {
            document.getElementById('detail-nama').innerText = profile.user?.name || '-';
            document.getElementById('detail-alamat').innerText = profile.alamat || '-';
            document.getElementById('detail-tempat-lahir').innerText = profile.tempat_lahir || '-';
            document.getElementById('detail-tanggal-lahir').innerText = profile.tanggal_lahir || '-';
            document.getElementById('detail-pekerjaan').innerText = profile.pekerjaan || '-';
            document.getElementById('detail-jenis-simpanan').innerText = profile.jenis_simpanan || '-';
            document.getElementById('detail-tabungan-pokok').innerText = profile.tabungan_pokok || '-';
            document.getElementById('detail-status').innerText = profile.status || '-';

            document.getElementById('detail-ktp').src = '/storage/' + profile.foto_ktp;
            document.getElementById('detail-kk').src = '/storage/' + profile.foto_kk;
            document.getElementById('detail-diri').src = '/storage/' + profile.foto_diri;

            new bootstrap.Modal(document.getElementById('detailProfileModal')).show();
        }
    </script>

@endsection
