<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanPayment;
use App\Models\Saving;

class TransactionHistoryUserController extends Controller
{
    public function index()
    {
        $loanPayments = LoanPayment::with(['user', 'loanApplication'])
            ->orderBy('created_at', 'desc')
            ->get();

        $savings = Saving::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $transactions = $loanPayments->concat($savings)
            ->sortByDesc('created_at')
            ->map(function ($item) {
                $item->user_name = optional($item->user)->name ?? 'N/A';

                if ($item instanceof LoanPayment) {
                    $item->loan_reference = $item->loanApplication ? '#' . $item->loanApplication->id : 'N/A';
                }

                return $item;
            });

        return view('petugas.transaction-history.index', compact('transactions'));
    }
}
