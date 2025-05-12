<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Models\User;

class ManagePengajuanPinjamanUserController extends Controller
{
    public function index()
    {
        $pendingApplications = LoanApplication::with('user')
            ->where('status', 'pending')
            ->get();

        $petugasList = User::where('role_id', 4)->get();

        return view('supervisor.manage-pengajuan-pinjaman.index', [
            'pendingApplications' => $pendingApplications,
            'petugasList' => $petugasList
        ]);
    }

    public function show($id)
    {
        $application = LoanApplication::with(['user', 'petugas'])->findOrFail($id);
        return view('supervisor.manage-pengajuan-pinjaman.show', compact('application'));
    }

    public function update(Request $request, $id)
    {
        $application = LoanApplication::findOrFail($id);

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:pending,accepted,rejected'
            ]);

            $application->status = $request->status;

            if ($request->status == 'rejected') {
                $application->petugas_id = null;
            }
        }

        if ($request->has('petugas_id')) {
            $request->validate([
                'petugas_id' => 'required|exists:users,id'
            ]);

            $application->petugas_id = $request->petugas_id;
        }

        $application->save();

        return redirect()->back()->with('success', 'Pengajuan pinjaman berhasil diperbarui');
    }
}
