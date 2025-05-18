@extends('layouts.main')

@section('title', 'Daftar Profil Peminjam')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Profil Peminjam</h4>
                        @if (!$hasProfile)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#createBorrowerProfileModal">
                                Tambah Profil Peminjam
                            </button>
                        @else
                        @endif
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 25px!important">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Profil Pengguna</th>
                                        <th>Nama User</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Tempat Lahir</th>
                                        <th>Pekerjaan</th>
                                        <th>Jenis Simpanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profiles as $profile)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $profile->id }}</td>
                                            {{-- profil pengguna --}}
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="avatar avatar-lg">
                                                        <img src="{{ Storage::url('') . $profile->foto_diri }}" alt="avatar">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $profile->user->name }}</td>
                                            <td>{{ $profile->alamat }}</td>
                                            <td>{{ $profile->tanggal_lahir->format('d-m-Y') }}</td>
                                            <td>{{ $profile->tempat_lahir }}</td>
                                            <td>{{ $profile->pekerjaan }}</td>
                                            <td>{{ ucfirst($profile->jenis_simpanan) }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $profile->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $profile->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="showDetailModal({{ json_encode($profile) }}, '{{ $profile->user->name }}')">
                                                                Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $profile->id }}, {{ $profile->user_id }}, '{{ $profile->alamat }}', '{{ $profile->tanggal_lahir->format('Y-m-d') }}', '{{ $profile->tempat_lahir }}', '{{ $profile->pekerjaan }}', '{{ $profile->jenis_simpanan }}', '{{ $profile->tabungan_pokok }}')">
                                                                Ubah
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('borrower-profiles.destroy', $profile->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil peminjam ini?')">
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

    <!-- Create Modal -->
    <div class="modal fade" id="createBorrowerProfileModal" tabindex="-1" aria-labelledby="createBorrowerProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBorrowerProfileModalLabel">Tambah Profil Peminjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createBorrowerProfileForm" method="POST" action="{{ route('borrower-profiles.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createUserId" class="form-label">Nama</label>
                                    <select class="form-select" id="createUserId" name="user_id" required>
                                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="createAlamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="createAlamat" name="alamat" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="createTanggalLahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="createTanggalLahir" name="tanggal_lahir"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="createTempatLahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="createTempatLahir" name="tempat_lahir"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createPekerjaan" class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" id="createPekerjaan" name="pekerjaan"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="createJenisSimpanan" class="form-label">Jenis Simpanan</label>
                                    <select class="form-select" id="createJenisSimpanan" name="jenis_simpanan" required>
                                        <option value="pokok">Pokok</option>
                                        <option value="wajib">Wajib</option>
                                        <option value="sukarela">Sukarela</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tabungan_pokok" class="form-label">Tabungan Pokok (Rp)</label>
                                    <input type="number" name="tabungan_pokok" class="form-control" required
                                        placeholder="Masukkan jumlah tabungan pokok">
                                </div>
                                <div class="mb-3">
                                    <label for="createFotoKtp" class="form-label">Foto KTP</label>
                                    <input type="file" class="form-control" id="createFotoKtp" name="foto_ktp"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="createFotoKk" class="form-label">Foto KK</label>
                                    <input type="file" class="form-control" id="createFotoKk" name="foto_kk"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="createFotoDiri" class="form-label">Foto Diri</label>
                                    <input type="file" class="form-control" id="createFotoDiri" name="foto_diri"
                                        required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editBorrowerProfileModal" tabindex="-1" aria-labelledby="editBorrowerProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBorrowerProfileModalLabel">Edit Profil Peminjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBorrowerProfileForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProfileId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUserId" class="form-label">User</label>
                                    <select class="form-select" id="editUserId" name="user_id" required>
                                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                    </select>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editAlamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="editAlamat" name="alamat" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editTanggalLahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="editTanggalLahir"
                                        name="tanggal_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editTempatLahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="editTempatLahir" name="tempat_lahir"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPekerjaan" class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" id="editPekerjaan" name="pekerjaan"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="editJenisSimpanan" class="form-label">Jenis Simpanan</label>
                                    <select class="form-select" id="editJenisSimpanan" name="jenis_simpanan" required>
                                        <option value="pokok">Pokok</option>
                                        <option value="wajib">Wajib</option>
                                        <option value="sukarela">Sukarela</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editTabunganPokok" class="form-label">Tabungan Pokok (Rp)</label>
                                    <input type="number" name="tabungan_pokok" id="editTabunganPokok"
                                        class="form-control" required placeholder="Masukkan jumlah tabungan pokok">
                                </div>
                                <div class="mb-3">
                                    <label for="editFotoKtp" class="form-label">Foto KTP</label>
                                    <input type="file" class="form-control" id="editFotoKtp" name="foto_ktp">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                </div>
                                <div class="mb-3">
                                    <label for="editFotoKk" class="form-label">Foto KK</label>
                                    <input type="file" class="form-control" id="editFotoKk" name="foto_kk">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                </div>
                                <div class="mb-3">
                                    <label for="editFotoDiri" class="form-label">Foto Diri</label>
                                    <input type="file" class="form-control" id="editFotoDiri" name="foto_diri">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailBorrowerProfileModal" tabindex="-1"
        aria-labelledby="detailBorrowerProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailBorrowerProfileModalLabel">Detail Profil Peminjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama User</label>
                                <p id="detailUserName" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <p id="detailAlamat" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <p id="detailTanggalLahir" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tempat Lahir</label>
                                <p id="detailTempatLahir" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pekerjaan</label>
                                <p id="detailPekerjaan" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Simpanan</label>
                                <p id="detailJenisSimpanan" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tabungan Pokok (Rp)</label>
                                <p id="detailTabunganPokok" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto KTP</label>
                                <a id="detailFotoKtpLink" href="#" target="_blank" class="d-block">
                                    <img id="detailFotoKtpPreview" src="" alt="Foto KTP" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto KK</label>
                                <a id="detailFotoKkLink" href="#" target="_blank" class="d-block">
                                    <img id="detailFotoKkPreview" src="" alt="Foto KK" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Diri</label>
                                <a id="detailFotoDiriLink" href="#" target="_blank" class="d-block">
                                    <img id="detailFotoDiriPreview" src="" alt="Foto Diri" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, userId, alamat, tanggalLahir, tempatLahir, pekerjaan, jenisSimpanan, tabunganPokok) {
            document.getElementById('editProfileId').value = id;
            document.getElementById('editUserId').value = userId;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editTanggalLahir').value = tanggalLahir;
            document.getElementById('editTempatLahir').value = tempatLahir;
            document.getElementById('editPekerjaan').value = pekerjaan;
            document.getElementById('editJenisSimpanan').value = jenisSimpanan;
            document.getElementById('editTabunganPokok').value = tabunganPokok;

            document.getElementById('editBorrowerProfileForm').action = 'borrower-profiles/' + id;

            var myModal = new bootstrap.Modal(document.getElementById('editBorrowerProfileModal'));
            myModal.show();
        }
    </script>
    <script>
        function showDetailModal(profile, userName) {
            document.getElementById('detailUserName').textContent = userName;
            document.getElementById('detailAlamat').textContent = profile.alamat;
            document.getElementById('detailTanggalLahir').textContent = new Date(profile.tanggal_lahir).toLocaleDateString(
                'id-ID');
            document.getElementById('detailTempatLahir').textContent = profile.tempat_lahir;
            document.getElementById('detailPekerjaan').textContent = profile.pekerjaan;
            document.getElementById('detailJenisSimpanan').textContent = profile.jenis_simpanan.charAt(0).toUpperCase() +
                profile.jenis_simpanan.slice(1);

            document.getElementById('detailTabunganPokok').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                profile.tabungan_pokok);

            const baseUrl = '{{ Storage::url('') }}';
            document.getElementById('detailFotoKtpPreview').src = baseUrl + profile.foto_ktp;
            document.getElementById('detailFotoKkPreview').src = baseUrl + profile.foto_kk;
            document.getElementById('detailFotoDiriPreview').src = baseUrl + profile.foto_diri;

            document.getElementById('detailFotoKtpLink').href = baseUrl + profile.foto_ktp;
            document.getElementById('detailFotoKkLink').href = baseUrl + profile.foto_kk;
            document.getElementById('detailFotoDiriLink').href = baseUrl + profile.foto_diri;

            var detailModal = new bootstrap.Modal(document.getElementById('detailBorrowerProfileModal'));
            detailModal.show();
        }
    </script>
@endsection
