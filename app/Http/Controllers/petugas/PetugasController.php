<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanPayment;
use App\Models\Saving;
use App\Models\User;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todaySavings = Saving::whereDate('created_at', $today)->count();
        $todayLoanPayments = LoanPayment::whereDate('created_at', $today)->count();

        $totalSavings = Saving::count();
        $totalLoanPayments = LoanPayment::count();

        $overdueLoans = LoanApplication::where('sisa_durasi_pinjaman', '>', 0)
            ->whereHas('payments', function ($query) use ($today) {
                $query->whereDate('tanggal_pembayaran', '<', $today->subMonth());
            }, '<', 1)
            ->count();

        $dates = [];
        $savingData = [];
        $paymentData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d M');

            $savingData[] = Saving::whereDate('created_at', $date)->count();
            $paymentData[] = LoanPayment::whereDate('created_at', $date)->count();
        }

        return view('petugas.dashboard', compact(
            'todaySavings',
            'todayLoanPayments',
            'totalSavings',
            'totalLoanPayments',
            'overdueLoans',
            'dates',
            'savingData',
            'paymentData'
        ));
    }
}
