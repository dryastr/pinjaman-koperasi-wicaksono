<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = LoanApplication::with(['user', 'payments'])
            ->where('user_id', auth()->id())
            ->get();

        $warnings = [];
        $today = Carbon::now();

        foreach ($loans as $loan) {
            if ($loan->status !== 'accepted') {
                continue;
            }

            $payments = $loan->payments->sortByDesc('tanggal_pembayaran');
            $loanDate = Carbon::parse($loan->created_at);

            if ($payments->isEmpty()) {
                if ($loanDate->diffInWeeks($today) >= 3) {
                    $warnings[] = "Pinjaman ID #{$loan->id} - Belum membayar sama sekali selama 3 minggu!";
                }
                continue;
            }

            $lastPaymentDate = Carbon::parse($payments->first()->tanggal_pembayaran);

            $allWeeksMissing = true;

            for ($i = 1; $i <= 3; $i++) {
                $weekToCheck = $lastPaymentDate->copy()->addWeeks($i);

                if ($weekToCheck->isFuture()) {
                    $allWeeksMissing = false;
                    break;
                }
                
                $paymentInWeek = $payments->contains(function ($payment) use ($weekToCheck) {
                    return Carbon::parse($payment->tanggal_pembayaran)
                        ->between(
                            $weekToCheck->copy()->startOfWeek(),
                            $weekToCheck->copy()->endOfWeek()
                        );
                });

                if ($paymentInWeek) {
                    $allWeeksMissing = false;
                    break;
                }
            }

            if ($allWeeksMissing) {
                $warnings[] = "Pinjaman ID #{$loan->id} - Tidak ada pembayaran selama 3 minggu berturut-turut!";
            }
        }

        return view('user.loan-applications.index', compact('loans', 'warnings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pinjaman' => 'required|string|max:255',
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'durasi_bulan' => 'required|integer|min:1|max:36',
        ]);

        LoanApplication::create([
            'user_id' => auth()->id(),
            'jenis_pinjaman' => $request->jenis_pinjaman,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'durasi_bulan' => $request->durasi_bulan,
            'sisa_durasi_pinjaman' => $request->durasi_bulan,
        ]);

        return redirect()->route('loan-applications.index')
            ->with('success', 'Pengajuan pinjaman berhasil dibuat.');
    }

    public function update(Request $request, LoanApplication $loanApplication)
    {
        if ($loanApplication->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'jenis_pinjaman' => 'required|string|max:255',
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'durasi_bulan' => 'required|integer|min:1|max:36',
        ]);

        $loanApplication->update([
            'jenis_pinjaman' => $request->jenis_pinjaman,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'durasi_bulan' => $request->durasi_bulan,
        ]);

        return redirect()->route('loan-applications.index')
            ->with('success', 'Pengajuan pinjaman berhasil diperbarui.');
    }

    public function destroy(LoanApplication $loanApplication)
    {
        if ($loanApplication->user_id !== auth()->id()) {
            abort(403);
        }

        $loanApplication->delete();

        return redirect()->route('loan-applications.index')
            ->with('success', 'Pengajuan pinjaman berhasil dihapus.');
    }

    public function printActiveLoans()
    {
        $loans = LoanApplication::with(['user', 'payments'])
            ->where('user_id', auth()->id())
            ->where('sisa_durasi_pinjaman', '>', 0)
            ->get();

        return view('user.loan-applications.print', compact('loans'));
    }
}
