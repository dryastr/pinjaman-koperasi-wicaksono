@extends('layouts.main')

@section('title', 'Pembayaran Pinjaman')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Pembayaran Pinjaman</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#transactionModal">
                            Tambah Pembayaran Pinjaman dan Simpanan
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 25px!important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <th>Pinjaman</th>
                                        <th>Jumlah Dibayar</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Metode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $payment->user->name }}</td>
                                            <td>Pinjaman #{{ $payment->loan_application_id }}</td>
                                            <td>Rp {{ number_format($payment->jumlah_dibayar, 0, ',', '.') }}</td>
                                            <td>{{ $payment->tanggal_pembayaran->format('d M Y') }}</td>
                                            <td>{{ ucfirst($payment->metode_pembayaran) }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $payment->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $payment->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="showDetailModal({{ json_encode($payment) }}, '{{ optional($payment->loanApplication)->jenis_pinjaman }}', '{{ $payment->user->name }}')">
                                                                Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditPaymentModal({{ json_encode($payment) }})">
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('loan-payments.destroy', $payment->id) }}"
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Form Simpanan --}}
                    <form id="savingForm" class="mb-4 border-bottom pb-4">
                        @csrf
                        <h6 class="fw-bold">Pembayaran Simpanan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="createUserId" class="form-label">Anggota</label>
                                <select class="form-select" id="createUserId" name="user_id" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createWajibAmount" class="form-label">Jumlah Simpanan Wajib (Rp)</label>
                                <input type="number" class="form-control" id="createWajibAmount" name="wajib_amount"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createSukarelaAmount" class="form-label">Jumlah Simpanan Sukarela (Rp)</label>
                                <input type="number" class="form-control" id="createSukarelaAmount" name="sukarela_amount"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createDate" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="createDate" name="date" required>
                            </div>
                            <div class="col-md-6 mb-3 d-none">
                                <label for="createAmount" class="form-label">Jumlah (Rp)</label>
                                <input type="hidden" class="form-control" id="createAmount" name="amount" value="0"
                                    required>
                            </div>
                        </div>
                    </form>

                    {{-- Form Pinjaman --}}
                    <form id="paymentForm" class="mb-4 border-bottom pb-4" enctype="multipart/form-data">
                        @csrf
                        <h6 class="fw-bold">Pembayaran Pinjaman</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Anggota</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="loan_application_id" class="form-label">Pinjaman</label>
                                <select class="form-select" id="loan_application_id" name="loan_application_id" required>
                                    <option value="">Pilih Anggota Terlebih Dahulu</option>
                                </select>
                                <small class="text-muted" id="loan_info"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="jumlah_dibayar" class="form-label">Jumlah Dibayar (Rp)</label>
                                <input type="number" class="form-control" id="jumlah_dibayar" name="jumlah_dibayar"
                                    min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" class="form-control" id="tanggal_pembayaran"
                                    name="tanggal_pembayaran" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="tunai">Tunai</option>
                                    <option value="non tunai">Non Tunai</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran"
                                    accept="image/*" required>
                                <small class="text-muted">Format: JPEG, PNG, JPG (Maks 2MB)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                            </div>
                        </div>
                    </form>

                    {{-- Form Iuran --}}
                    <form id="incomeForm" enctype="multipart/form-data">
                        @csrf
                        <h6 class="fw-bold">Iuran</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="incomeUserId" class="form-label">Penerima</label>
                                <select class="form-select" id="incomeUserId" name="user_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="incomeAmount" class="form-label">Jumlah (Rp)</label>
                                <input type="number" class="form-control" id="incomeAmount" name="amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="incomeDescription" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" id="incomeDescription" name="description"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="incomePaymentDate" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" class="form-control" id="incomePaymentDate" name="payment_date"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="incomePaymentMethod" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="incomePaymentMethod" name="payment_method" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="incomeProof" class="form-label">Bukti Pembayaran</label>
                                <input type="file" class="form-control" id="incomeProof" name="proof"
                                    accept="image/*" required>
                                <small class="text-muted">Format: JPEG, PNG, JPG (Maks 2MB)</small>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitAll">Simpan Semua</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Edit Pembayaran Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPaymentForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editPaymentId" name="id">

                        <div class="mb-3">
                            <label for="editUserId" class="form-label">Anggota</label>
                            <select class="form-select" id="editUserId" name="user_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editLoanId" class="form-label">Pinjaman</label>
                            <select class="form-select" id="editLoanId" name="loan_application_id" required>
                                <option value="">Pilih Anggota Terlebih Dahulu</option>
                            </select>
                            <small class="text-muted" id="editLoanInfo"></small>
                        </div>

                        <div class="mb-3">
                            <label for="editJumlah" class="form-label">Jumlah Dibayar</label>
                            <input type="number" class="form-control" id="editJumlah" name="jumlah_dibayar" required>
                        </div>

                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="editTanggal" name="tanggal_pembayaran"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="editMetode" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="editMetode" name="metode_pembayaran" required>
                                <option value="tunai">Tunai</option>
                                <option value="non tunai">Non Tunai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editBukti" class="form-label">Bukti Pembayaran Baru (Opsional)</label>
                            <input type="file" class="form-control" id="editBukti" name="bukti_pembayaran"
                                accept="image/*">
                            <small class="text-muted">Format: JPEG, PNG, JPG (Maks 2MB)</small>
                            <div id="currentBukti" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editCatatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="editCatatan" name="catatan" rows="3" maxlength="500"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPaymentModalLabel">Detail Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Anggota</label>
                        <p id="detailNamaAnggota" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pinjaman</label>
                        <p id="detailPinjaman" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Dibayar</label>
                        <p id="detailJumlahDibayar" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pembayaran</label>
                        <p id="detailTanggalPembayaran" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <p id="detailMetodePembayaran" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p id="detailStatus" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bukti Pembayaran</label>
                        <a id="detailBuktiLink" href="#" target="_blank" class="d-block">
                            <img id="detailBuktiPreview" src="" alt="Bukti Pembayaran" class="img-thumbnail"
                                style="max-height: 200px;">
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan</label>
                        <p id="detailCatatan" class="form-control-plaintext"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(payment, jenisPinjaman, namaAnggota) {
            document.getElementById('detailNamaAnggota').textContent = namaAnggota;
            document.getElementById('detailPinjaman').textContent = 'Pinjaman #' + payment.loan_application_id + ' - ' +
                jenisPinjaman;
            document.getElementById('detailJumlahDibayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                payment.jumlah_dibayar);
            document.getElementById('detailTanggalPembayaran').textContent = new Date(payment.tanggal_pembayaran)
                .toLocaleDateString('id-ID');
            document.getElementById('detailMetodePembayaran').textContent = payment.metode_pembayaran.charAt(0)
                .toUpperCase() + payment.metode_pembayaran.slice(1);
            document.getElementById('detailStatus').textContent = 'Berhasil';
            document.getElementById('detailCatatan').textContent = payment.catatan || '-';

            const baseUrl = '{{ Storage::url('') }}';
            document.getElementById('detailBuktiPreview').src = baseUrl + payment.bukti_pembayaran;
            document.getElementById('detailBuktiLink').href = baseUrl + payment.bukti_pembayaran;

            var detailModal = new bootstrap.Modal(document.getElementById('detailPaymentModal'));
            detailModal.show();
        }

        document.getElementById('tanggal_pembayaran').valueAsDate = new Date();
    </script>

    <script>
        function openEditPaymentModal(payment) {
            document.getElementById('editPaymentId').value = payment.id;
            document.getElementById('editUserId').value = payment.user_id;
            document.getElementById('editJumlah').value = payment.jumlah_dibayar;
            const tanggalPembayaran = new Date(payment.tanggal_pembayaran);
            const formattedDate = tanggalPembayaran.toISOString().split('T')[0];

            document.getElementById('editTanggal').value = formattedDate;
            document.getElementById('editMetode').value = payment.metode_pembayaran;
            document.getElementById('editCatatan').value = payment.catatan || '';

            const currentBukti = document.getElementById('currentBukti');
            if (payment.bukti_pembayaran) {
                currentBukti.innerHTML = `
            <p>Bukti saat ini:</p>
            <img src="{{ asset('storage/${payment.bukti_pembayaran}') }}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 150px;">
        `;
            } else {
                currentBukti.innerHTML = '<p>Tidak ada bukti pembayaran</p>';
            }

            document.getElementById('editPaymentForm').action = '{{ route('loan-payments.update', '') }}/' + payment.id;

            loadEditUserLoans(payment.user_id, payment.loan_application_id);

            var editModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
            editModal.show();
        }

        function loadEditUserLoans(userId, selectedLoanId = null) {
            const loanSelect = document.getElementById('editLoanId');
            loanSelect.innerHTML = '<option value="">Memuat Pinjaman...</option>';

            if (!userId) {
                loanSelect.innerHTML = '<option value="">Pilih Anggota Terlebih Dahulu</option>';
                return;
            }

            fetch(`{{ url('get-user-loans') }}/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    loanSelect.innerHTML = '<option value="">Pilih Pinjaman</option>';

                    if (data.length === 0) {
                        loanSelect.innerHTML = '<option value="">Tidak Ada Pinjaman Aktif</option>';
                        return;
                    }

                    data.forEach(loan => {
                        const option = document.createElement('option');
                        option.value = loan.id;
                        option.textContent =
                            `Pinjaman #${loan.id} - ${loan.jenis_pinjaman} (Rp ${new Intl.NumberFormat('id-ID').format(loan.jumlah_pinjaman)}) - Sisa ${loan.sisa_durasi_pinjaman} bulan`;

                        if (loan.id == selectedLoanId) {
                            option.selected = true;
                        }

                        loanSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading loans:', error);
                    loanSelect.innerHTML = '<option value="">Error loading loans</option>';
                });
        }

        document.getElementById('editUserId').addEventListener('change', function() {
            const userId = this.value;
            loadEditUserLoans(userId);
        });
    </script>
    <script>
        document.getElementById('submitAll').addEventListener('click', async function() {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

            try {
                const savingForm = document.getElementById('savingForm');
                const paymentForm = document.getElementById('paymentForm');
                const incomeForm = document.getElementById('incomeForm');

                const savingData = new FormData(savingForm);
                const paymentData = new FormData(paymentForm);
                const incomeData = new FormData(incomeForm);

                const [savingRes, paymentRes, incomeRes] = await Promise.all([
                    fetch("{{ route('savings.storejson') }}", {
                        method: 'POST',
                        body: savingData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }),
                    fetch("{{ route('loan-payments.store') }}", {
                        method: 'POST',
                        body: paymentData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }),
                    fetch("{{ route('office-incomes-json.store') }}", {
                        method: 'POST',
                        body: incomeData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                ]);

                const savingIsJson = savingRes.headers.get('content-type')?.includes('application/json');
                const paymentIsJson = paymentRes.headers.get('content-type')?.includes('application/json');
                const incomeIsJson = incomeRes.headers.get('content-type')?.includes('application/json');

                if (!savingIsJson || !paymentIsJson || !incomeIsJson) {
                    throw new Error('Format respons server tidak valid');
                }

                const [savingResult, paymentResult, incomeResult] = await Promise.all([
                    savingRes.json(),
                    paymentRes.json(),
                    incomeRes.json()
                ]);

                const errors = [];
                if (!savingResult.success) errors.push(`Simpanan: ${savingResult.message}`);
                if (!paymentResult.success) errors.push(`Pinjaman: ${paymentResult.message}`);
                if (!incomeResult.success) errors.push(`Pendapatan: ${incomeResult.message}`);

                if (errors.length > 0) {
                    throw new Error(errors.join('\n'));
                }

                alert('Semua transaksi berhasil disimpan!');
                window.location.href = savingResult.redirect || "{{ route('loan-payments.index') }}";

            } catch (error) {
                console.error('Error:', error);

                if (error.message.includes('NetworkError')) {
                    alert('Koneksi jaringan bermasalah. Silakan coba lagi.');
                } else if (error.message.includes('respons server')) {
                    alert('Terjadi kesalahan pada server. Silakan hubungi administrator.');
                } else {
                    alert(`Gagal menyimpan:\n${error.message}`);
                }
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Simpan Semua';
            }
        });

        document.getElementById('createUserId').addEventListener('change', function() {
            const selectedUserId = this.value;

            document.getElementById('user_id').value = selectedUserId;

            document.getElementById('incomeUserId').value = selectedUserId;

            if (selectedUserId) {
                loadUserLoans();
            }
        });

        function loadUserLoans() {
            const userId = document.getElementById('user_id').value;
            const loanSelect = document.getElementById('loan_application_id');

            loanSelect.innerHTML = '<option value="">Memuat Pinjaman...</option>';

            if (!userId) {
                loanSelect.innerHTML = '<option value="">Pilih Anggota Terlebih Dahulu</option>';
                return;
            }

            fetch(`{{ url('get-user-loans') }}/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    loanSelect.innerHTML = '<option value="">Pilih Pinjaman</option>';

                    if (data.length === 0) {
                        loanSelect.innerHTML = '<option value="">Tidak Ada Pinjaman Aktif</option>';
                        return;
                    }

                    data.forEach(loan => {
                        if (loan.sisa_durasi_pinjaman > 0) {
                            const option = document.createElement('option');
                            option.value = loan.id;
                            option.textContent =
                                `Pinjaman #${loan.id} - ${loan.jenis_pinjaman} (Rp ${new Intl.NumberFormat('id-ID').format(loan.jumlah_pinjaman)}) - Sisa ${loan.sisa_durasi_pinjaman} bulan`;
                            loanSelect.appendChild(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading loans:', error);
                    loanSelect.innerHTML = '<option value="">Error loading loans</option>';
                });
        }
    </script>

@endsection
