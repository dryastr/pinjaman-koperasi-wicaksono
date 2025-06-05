<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowerProfile;
use App\Models\LoanApplication;
use App\Models\OfficeIncome;
use App\Models\Saving;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $qardhLoans = LoanApplication::where('jenis_pinjaman', 'qardh')->count();
        $bisnisLoans = LoanApplication::where('jenis_pinjaman', 'bisnis')->count();
        $totalLoans = $qardhLoans + $bisnisLoans;

        $totalAmountSavings = Saving::where('status', 'approved')->sum('amount');
        $totalWajibAmountSavings = Saving::where('status', 'approved')->sum('wajib_amount');
        $totalSukarelaAmountSavings = Saving::where('status', 'approved')->sum('sukarela_amount');

        $totalSavings = $totalAmountSavings + $totalWajibAmountSavings + $totalSukarelaAmountSavings;

        $iuran = OfficeIncome::all()->sum('amount');

        $totalAmountLoans = LoanApplication::where('status', 'accepted')->sum('jumlah_pinjaman');

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'accepted')->count();

        $loanChartData = [
            'qardh' => $qardhLoans,
            'bisnis' => $bisnisLoans
        ];

        $savingChartData = [
            'amount' => $totalAmountSavings,
            'wajib_amount' => $totalWajibAmountSavings,
            'sukarela_amount' => $totalSukarelaAmountSavings
        ];

        return view('admin.dashboard', compact(
            'qardhLoans',
            'bisnisLoans',
            'totalLoans',
            'totalAmountSavings',
            'totalWajibAmountSavings',
            'totalSukarelaAmountSavings',
            'totalSavings',
            'totalUsers',
            'activeUsers',
            'loanChartData',
            'savingChartData',
            'iuran',
            'totalAmountLoans'
        ));
    }
}
