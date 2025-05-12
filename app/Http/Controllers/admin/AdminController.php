<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowerProfile;
use App\Models\LoanApplication;
use App\Models\Saving;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $qardhLoans = LoanApplication::where('jenis_pinjaman', 'qardh')->count();
        $bisnisLoans = LoanApplication::where('jenis_pinjaman', 'bisnis')->count();
        $totalLoans = $qardhLoans + $bisnisLoans;

        $pokokSavings = Saving::where('type', 'pokok')->where('status', 'approved')->count();
        $wajibSavings = Saving::where('type', 'wajib')->where('status', 'approved')->count();
        $sukarelaSavings = Saving::where('type', 'sukarela')->where('status', 'approved')->count();
        $totalSavings = $pokokSavings + $wajibSavings + $sukarelaSavings;

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'accepted')->count();

        $loanChartData = [
            'qardh' => $qardhLoans,
            'bisnis' => $bisnisLoans
        ];

        $savingChartData = [
            'pokok' => $pokokSavings,
            'wajib' => $wajibSavings,
            'sukarela' => $sukarelaSavings
        ];

        return view('admin.dashboard', compact(
            'qardhLoans',
            'bisnisLoans',
            'totalLoans',
            'pokokSavings',
            'wajibSavings',
            'sukarelaSavings',
            'totalSavings',
            'totalUsers',
            'activeUsers',
            'loanChartData',
            'savingChartData'
        ));
    }
}
