<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use Illuminate\Http\Request;

class ApprovalSavingController extends Controller
{
    /**
     * Display a listing of all savings.
     */
    public function index()
    {
        $savings = Saving::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.approval-savings.index', compact('savings'));
    }

    /**
     * Update the saving status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $saving = Saving::findOrFail($id);

        $saving->status = $request->status;
        $saving->save();

        return redirect()->route('approval-savings.index')
            ->with('success', 'Status simpanan berhasil diperbarui.');
    }

    /**
     * Remove the specified saving.
     */
    public function destroy(Saving $saving)
    {
        $saving->delete();

        return redirect()->route('approval-savings.index')
            ->with('success', 'Simpanan berhasil dihapus.');
    }
}
