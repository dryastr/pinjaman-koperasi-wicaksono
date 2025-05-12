<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
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

        return view('user.loan-applications.index', compact('loans'));
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
}
