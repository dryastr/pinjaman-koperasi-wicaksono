@extends('layouts.main')

@section('title', 'Ubah Password')

@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header">
                <h5>Ubah Password</h5>
            </div>
            <div class="card-body">
                {{-- @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif --}}

                <form method="POST" action="{{ route('user.password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
