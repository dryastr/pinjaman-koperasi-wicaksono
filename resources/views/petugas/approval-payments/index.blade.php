@extends('layouts.main')

@section('title', 'Approval Pembayaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Pembayaran User</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab"
                                    data-bs-target="#pending" type="button" role="tab">Pending</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                                    type="button" role="tab">Approved</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                                    type="button" role="tab">Rejected</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3" id="paymentTabsContent">
                            @foreach (['pending', 'approved', 'rejected'] as $tab)
                                <div class="tab-pane fade {{ $tab == 'pending' ? 'show active' : '' }}"
                                    id="{{ $tab }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-xl" style="margin-top: 25px!important;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>User</th>
                                                    <th>Pinjaman</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments->where('status', $tab) as $payment)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $payment->user->name }}</td>
                                                        <td>Pinjaman #{{ $payment->loan_application_id }}</td>
                                                        <td>Rp {{ number_format($payment->jumlah_dibayar, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('d M Y') }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $tab == 'approved' ? 'success' : ($tab == 'rejected' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($tab) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <div class="dropdown dropup">
                                                                <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton-{{ $payment->id }}"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton-{{ $payment->id }}">
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="openEditModal(
                                                                {{ $payment->id }},
                                                                '{{ $payment->status }}',
                                                                {{ json_encode($payment->loanApplication) }},
                                                                {{ json_encode($payment->user) }}
                                                            )">
                                                                            Ubah Status
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ Storage::url('public/' . $payment->bukti_pembayaran) }}"
                                                                            target="_blank">
                                                                            Lihat Bukti
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('approval-payments.destroy', $payment->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
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
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Ubah Status Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Detail Pinjaman</h6>
                            <div class="border p-3 rounded">
                                <div class="mb-2">
                                    <strong>Jenis Pinjaman:</strong>
                                    <span id="loanType"></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Jumlah Pinjaman:</strong>
                                    <span id="loanAmount"></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Durasi:</strong>
                                    <span id="loanDuration"></span> bulan
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Detail User</h6>
                            <div class="border p-3 rounded">
                                <div class="mb-2">
                                    <strong>Nama:</strong>
                                    <span id="userName"></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <span id="userEmail"></span>
                                </div>
                                <div>
                                    <strong>Tanggal Daftar:</strong>
                                    <span id="userRegDate"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <h6>Detail Pembayaran</h6>
                        <form id="editPaymentForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="editPaymentId">
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Status Pembayaran</label>
                                <select class="form-select" id="editStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, status, loanData, userData) {

            document.getElementById('editPaymentId').value = id;
            document.getElementById('editStatus').value = status;


            document.getElementById('loanType').textContent = loanData.jenis_pinjaman;
            document.getElementById('loanAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(loanData
                .jumlah_pinjaman);
            document.getElementById('loanDuration').textContent = loanData.durasi_bulan;

            document.getElementById('userName').textContent = userData.name;
            document.getElementById('userEmail').textContent = userData.email;
            document.getElementById('userRegDate').textContent = new Date(userData.created_at).toLocaleDateString('id-ID');



            document.getElementById('editPaymentForm').action = '{{ route('approval-payments.update', '') }}/' + id;


            var editModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
            editModal.show();
        }
    </script>
@endsection
