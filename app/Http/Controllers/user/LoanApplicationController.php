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
        $loans = LoanApplication::with('user')
            ->where('user_id', auth()->id())
            ->get();

        $warnings = [];

        foreach ($loans as $loan) {
            $payments = LoanPayment::where('loan_application_id', $loan->id)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->pluck('tanggal_pembayaran');

            $now = Carbon::now();
            $missingWeeks = 0;

            for ($i = 0; $i < 3; $i++) {
                $startOfWeek = $now->copy()->subWeeks($i)->startOfWeek();
                $endOfWeek = $now->copy()->subWeeks($i)->endOfWeek();

                $paidThisWeek = $payments->contains(function ($paymentDate) use ($startOfWeek, $endOfWeek) {
                    return Carbon::parse($paymentDate)->between($startOfWeek, $endOfWeek);
                });

                if (!$paidThisWeek) {
                    $missingWeeks++;
                }
            }

            if ($missingWeeks === 3) {
                $warnings[] = "Pinjaman ID #{$loan->id} | {$loan->jenis_pinjaman} - Anda belum dibayar selama 3 minggu berturut-turut!";
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
