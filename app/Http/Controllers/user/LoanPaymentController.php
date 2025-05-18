<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LoanPaymentController extends Controller
{

    public function index()
    {
        $payments = LoanPayment::with(['loanApplication', 'user'])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->get();

        return view('user.loan-payments.index', compact('payments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_application_id' => 'required|exists:loan_applications,id',
            'jumlah_dibayar' => 'required|numeric|min:1',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,non tunai',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $loan = LoanApplication::findOrFail($request->loan_application_id);

                if ($loan->user_id != $request->user_id) {
                    throw ValidationException::withMessages([
                        'loan_application_id' => 'Pinjaman ini tidak terkait dengan anggota yang dipilih'
                    ]);
                }

                if ($loan->sisa_durasi_pinjaman <= 0) {
                    throw ValidationException::withMessages([
                        'loan_application_id' => 'Pinjaman ini sudah lunas'
                    ]);
                }

                $buktiPath = $request->file('bukti_pembayaran')->store('public/loan_payments');

                LoanPayment::create([
                    'loan_application_id' => $request->loan_application_id,
                    'user_id' => $request->user_id,
                    'jumlah_dibayar' => $request->jumlah_dibayar,
                    'tanggal_pembayaran' => $request->tanggal_pembayaran,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bukti_pembayaran' => str_replace('public/', '', $buktiPath),
                    'catatan' => $request->catatan,
                ]);

                $loan->decrement('sisa_durasi_pinjaman');
            });

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimpan.',
                'redirect' => route('loan-payments.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }


        // return redirect()->route('loan-payments.index')
        //     ->with('success', 'Pembayaran berhasil disimpan dan sisa durasi pinjaman telah diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_application_id' => 'required|exists:loan_applications,id',
            'jumlah_dibayar' => 'required|numeric|min:1',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,non tunai',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $id) {
            $payment = LoanPayment::findOrFail($id);
            $loan = LoanApplication::findOrFail($request->loan_application_id);

            if ($loan->user_id != $request->user_id) {
                throw ValidationException::withMessages([
                    'loan_application_id' => 'Pinjaman ini tidak terkait dengan anggota yang dipilih'
                ]);
            }

            $data = [
                'loan_application_id' => $request->loan_application_id,
                'user_id' => $request->user_id,
                'jumlah_dibayar' => $request->jumlah_dibayar,
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan,
            ];

            if ($request->hasFile('bukti_pembayaran')) {
                if ($payment->bukti_pembayaran) {
                    Storage::delete('public/' . $payment->bukti_pembayaran);
                }

                $buktiPath = $request->file('bukti_pembayaran')->store('public/loan_payments');
                $data['bukti_pembayaran'] = str_replace('public/', '', $buktiPath);
            }

            $payment->update($data);
        });

        return redirect()->route('loan-payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(LoanPayment $loanPayment)
    {
        Storage::delete('public/' . $loanPayment->bukti_pembayaran);
        $loanPayment->delete();

        return redirect()->route('loan-payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function getUserLoans($userId)
    {
        $loans = LoanApplication::where('user_id', $userId)
            ->where('sisa_durasi_pinjaman', '>', 0)
            ->get();

        return response()->json($loans);
    }
}
