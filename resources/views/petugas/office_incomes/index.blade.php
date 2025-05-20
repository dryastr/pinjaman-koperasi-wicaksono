@extends('layouts.main')

@section('title', 'Manajemen Pendapatan Kantor')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Iuran</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createIncomeModal">
                            Tambah Iuran
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
                                        <th>Pengguna</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        @unless (auth()->user()->isAdmin())
                                            <th>Aksi</th>
                                        @endunless
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incomes as $income)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $income->user->name }}</td>
                                            <td>{{ $income->description }}</td>
                                            <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                                            <td>{{ $income->payment_date->format('d M Y') }}</td>
                                            <td>{{ ucfirst($income->payment_method) }}</td>
                                            <td>
                                                @if ($income->proof)
                                                    <a href="{{ asset('storage/' . $income->proof) }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        Lihat
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @unless (auth()->user()->isAdmin())
                                                <td class="text-nowrap">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="openEditModal({{ $income->id }}, {{ $income->user_id }}, '{{ $income->description }}', {{ $income->amount }}, '{{ $income->payment_date->format('Y-m-d') }}', '{{ $income->payment_method }}')">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="{{ route('office-incomes.destroy', $income->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pendapatan ini?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endunless
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
    <div class="modal fade" id="createIncomeModal" tabindex="-1" aria-labelledby="createIncomeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createIncomeModalLabel">Tambah Pendapatan Kantor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createIncomeForm" method="POST" action="{{ route('office-incomes.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="createUserId" class="form-label">Penerima</label>
                            <select class="form-select" id="createUserId" name="user_id" required>
                                <option value="">Pilih Penerima</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createDescription" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="createDescription" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="createAmount" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" id="createAmount" name="amount" min="10000"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="createPaymentDate" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="createPaymentDate" name="payment_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="createPaymentMethod" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="createPaymentMethod" name="payment_method" required>
                                <option value="">Pilih Metode</option>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createProof" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="createProof" name="proof" accept="image/*"
                                required>
                            <small class="text-muted">Format: JPEG, PNG, JPG (Maks 2MB)</small>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editIncomeModalLabel">Edit Pendapatan Kantor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editIncomeForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editUserId" class="form-label">Penerima</label>
                            <select class="form-select" id="editUserId" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="editDescription" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAmount" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" id="editAmount" name="amount" min="10000"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editPaymentDate" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="editPaymentDate" name="payment_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editPaymentMethod" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="editPaymentMethod" name="payment_method" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProof" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="editProof" name="proof" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
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
        function openEditModal(id, userId, description, amount, paymentDate, paymentMethod) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editDescription').value = description;
            document.getElementById('editAmount').value = amount;
            document.getElementById('editPaymentDate').value = paymentDate;
            document.getElementById('editPaymentMethod').value = paymentMethod;

            document.getElementById('editIncomeForm').action = '{{ route('office-incomes.update', '') }}/' + id;

            var editModal = new bootstrap.Modal(document.getElementById('editIncomeModal'));
            editModal.show();
        }

        document.getElementById('createPaymentDate').valueAsDate = new Date();
    </script>
@endsection
