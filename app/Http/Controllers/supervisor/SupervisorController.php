<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\User;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')
            ->with('borrowerProfile')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = $pendingUsers->count();

        $approvedToday = User::where('status', 'accepted')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $totalMembers = User::where('status', 'accepted')->count();

        $approvalDates = [];
        $approvalData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $approvalDates[] = $date->format('d M');
            $approvalData[] = User::where('status', 'accepted')
                ->whereDate('updated_at', $date)
                ->count();
        }

        $pendingLoanApplications = LoanApplication::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingLoanCount = $pendingLoanApplications->count();


        return view('supervisor.dashboard', compact(
            'pendingUsers',
            'pendingCount',
            'approvedToday',
            'totalMembers',
            'approvalDates',
            'approvalData',
            'pendingLoanApplications',
            'pendingLoanCount'
        ));
    }

    public function approveUser(User $user)
    {
        $user->update([
            'status' => 'accepted',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Anggota berhasil disetujui');
    }

    public function rejectUser(User $user)
    {
        $user->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Anggota berhasil ditolak');
    }
}
