@extends('layouts.main')

@section('title', 'Approval Simpanan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Simpanan User</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="savingTabs" role="tablist">
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

                        <div class="tab-content pt-3" id="savingTabsContent">
                            @foreach (['pending', 'approved', 'rejected'] as $tab)
                                <div class="tab-pane fade {{ $tab == 'pending' ? 'show active' : '' }}"
                                    id="{{ $tab }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-xl" style="margin-top: 25px!important;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>User</th>
                                                    <th>Jumlah</th>
                                                    <th>Jenis</th>
                                                    <th>Tanggal</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($savings->where('status', $tab) as $saving)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $saving->user->name }}</td>
                                                        <td>Rp {{ number_format($saving->amount, 0, ',', '.') }}</td>
                                                        <td>{{ ucfirst($saving->type) }}</td>
                                                        <td>{{ $saving->date->format('d M Y') }}</td>
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
                                                                    id="dropdownMenuButton-{{ $saving->id }}"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton-{{ $saving->id }}">
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="openEditModal(
                                                                                {{ $saving->id }},
                                                                                '{{ $saving->status }}',
                                                                                {{ json_encode($saving) }},
                                                                                {{ json_encode($saving->user) }}
                                                                            )">
                                                                            Ubah
                                                                            Status
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('approval-savings.destroy', $saving->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus simpanan ini?')">
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
    <div class="modal fade" id="editSavingModal" tabindex="-1" aria-labelledby="editSavingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSavingModalLabel">Ubah Status Simpanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Detail Simpanan</h6>
                            <div class="border p-3 rounded">
                                <div class="mb-2">
                                    <strong>Jumlah:</strong>
                                    <span id="savingAmount">Rp 0</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Jenis:</strong>
                                    <span id="savingType">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Tanggal:</strong>
                                    <span id="savingDate">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Diajukan Pada:</strong>
                                    <span id="savingCreatedAt">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Detail User</h6>
                            <div class="border p-3 rounded">
                                <div class="mb-2">
                                    <strong>Nama:</strong>
                                    <span id="userName">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <span id="userEmail">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>No. Telepon:</strong>
                                    <span id="userPhone">-</span>
                                </div>
                                <div>
                                    <strong>Tanggal Daftar:</strong>
                                    <span id="userRegDate">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <form id="editSavingForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="editSavingId">
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Status Simpanan</label>
                                <select class="form-select" id="editStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status Saat Ini</label>
                                <p class="form-control-plaintext" id="currentStatus"></p>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, status, savingData, userData) {
            document.getElementById('editSavingId').value = id;
            document.getElementById('editStatus').value = status;

            const formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(savingData.amount);

            document.getElementById('savingAmount').textContent = formattedAmount;

            let savingType = '';
            switch (savingData.type) {
                case 'pokok':
                    savingType = 'Simpanan Pokok';
                    break;
                case 'wajib':
                    savingType = 'Simpanan Wajib';
                    break;
                case 'sukarela':
                    savingType = 'Simpanan Sukarela';
                    break;
                default:
                    savingType = savingData.type;
            }
            document.getElementById('savingType').textContent = savingType;

            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            document.getElementById('savingDate').textContent = new Date(savingData.date).toLocaleDateString('id-ID',
                options);
            document.getElementById('savingCreatedAt').textContent = new Date(savingData.created_at).toLocaleDateString(
                'id-ID', options);

            document.getElementById('currentStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);

            document.getElementById('userName').textContent = userData.name;
            document.getElementById('userEmail').textContent = userData.email;
            document.getElementById('userPhone').textContent = userData.phone ?? '-';
            document.getElementById('userRegDate').textContent = new Date(userData.created_at).toLocaleDateString('id-ID',
                options);

            document.getElementById('editSavingForm').action = '{{ route('approval-savings.update', '') }}/' + id;

            var editModal = new bootstrap.Modal(document.getElementById('editSavingModal'));
            editModal.show();
        }
    </script>
@endsection
