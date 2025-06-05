<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanPayment;
use App\Models\Saving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalSavings = Saving::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum(DB::raw('amount + wajib_amount + sukarela_amount'));

        $savingCount = Saving::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        $activeLoan = LoanApplication::where('user_id', $user->id)
            ->where('sisa_durasi_pinjaman', '>', 0)
            ->first();

        $loanAmount = $activeLoan ? $activeLoan->jumlah_pinjaman : 0;
        $remainingDuration = $activeLoan ? $activeLoan->sisa_durasi_pinjaman : 0;

        $totalPaid = $activeLoan ?
            LoanPayment::where('loan_application_id', $activeLoan->id)
            ->sum('jumlah_dibayar') : 0;

        // dd($totalSavings);

        return view('user.dashboard', compact(
            'totalSavings',
            'savingCount',
            'loanAmount',
            'remainingDuration',
            'totalPaid'
        ));
    }
}
