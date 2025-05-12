@extends('layouts.main')

@section('title', 'Detail User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail User</h4>
                    <a href="{{ route('view-users.index') }}" class="btn btn-secondary float-end">Kembali</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3">Informasi Dasar</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nama</label>
                                    <p>{{ $user->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p>{{ $user->email }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p>
                                        <span class="badge bg-success">Accepted</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Daftar</label>
                                    <p>{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($user->borrowerProfile)
                            <div class="mb-4">
                                <h5 class="mb-3">Informasi Tambahan</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Alamat</label>
                                        <p>{{ $user->borrowerProfile->alamat }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Tanggal Lahir</label>
                                        <p>{{ $user->borrowerProfile->tanggal_lahir }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Tempat Lahir</label>
                                        <p>{{ $user->borrowerProfile->tempat_lahir }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Pekerjaan</label>
                                        <p>{{ $user->borrowerProfile->pekerjaan }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Jenis Simpanan</label>
                                        <p>{{ $user->borrowerProfile->jenis_simpanan }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-3">Dokumen</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Foto KTP</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="{{ asset('storage/' . $user->borrowerProfile->foto_ktp) }}"
                                                    alt="Foto KTP" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Foto KK</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="{{ asset('storage/' . $user->borrowerProfile->foto_kk) }}"
                                                    alt="Foto KK" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Foto Diri</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="{{ asset('storage/' . $user->borrowerProfile->foto_diri) }}"
                                                    alt="Foto Diri" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
