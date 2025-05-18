<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SavingController extends Controller
{
    public function index()
    {
        $savings = Saving::with('user')
            ->orderBy('date', 'desc')
            ->get();

        $users = User::whereHas('role', function ($query) {
            $query
            ->where('name', 'user')
            ->where('status', 'accepted');
        })->get();

        return view('user.savings.index', compact('savings', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:10000',
            'type' => 'required|in:pokok,wajib,sukarela',
            'date' => 'required|date',
        ]);

        Saving::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
            'status' => 'pending',
        ]);

        return redirect()->route('savings.index')
            ->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function jsonStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:pokok,wajib,sukarela',
            'date' => 'required|date',
        ]);

        try {
            $saving = Saving::create($validated + ['status' => 'pending']);

            return response()->json([
                'success' => true,
                'message' => 'Simpanan berhasil ditambahkan',
                'data' => $saving,
                'redirect' => route('savings.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
                'errors' => $e->errors() ?? null
            ], 422);
        }
    }

    public function update(Request $request, Saving $saving)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:10000',
            'type' => 'required|in:pokok,wajib,sukarela',
            'date' => 'required|date',
        ]);

        $saving->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
        ]);

        return redirect()->route('savings.index')
            ->with('success', 'Simpanan berhasil diperbarui.');
    }

    /**
     * Remove the specified saving.
     */
    public function destroy(Saving $saving)
    {
        if ($saving->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya simpanan dengan status pending yang bisa dihapus');
        }

        $saving->delete();

        return redirect()->route('savings.index')
            ->with('success', 'Simpanan berhasil dihapus.');
    }
}
