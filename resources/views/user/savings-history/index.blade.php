@extends('layouts.main')

@section('title', 'History Simpanan Saya')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History Simpanan Saya</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jumlah</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($savings as $saving)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>Rp {{ number_format($saving->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $saving->type == 'pokok' ? 'primary' : ($saving->type == 'wajib' ? 'info' : 'secondary') }}">
                                                    {{ ucfirst($saving->type) }}
                                                </span>
                                            </td>
                                            <td>{{ $saving->date->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $saving->status == 'approved' ? 'success' : ($saving->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($saving->status) }}
                                                </span>
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
@endsection
