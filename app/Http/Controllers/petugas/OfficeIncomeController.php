<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\OfficeIncome;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeIncomeController extends Controller
{
    public function index()
    {
        $incomes = OfficeIncome::with('user')
            ->orderBy('payment_date', 'desc')
            ->get();

        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user')->where('status', 'accepted');
        })->get();

        return view('petugas.office_incomes.index', compact('incomes', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'proof' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('proof')) {
                $validated['proof'] = $request->file('proof')->store('proofs', 'public');
            }

            $income = OfficeIncome::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan berhasil ditambahkan',
                'data' => $income,
                'redirect' => route('office-incomes.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, OfficeIncome $officeIncome)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'proof' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('proof')) {
            $validated['proof'] = $request->file('proof')->store('proofs', 'public');
        }

        $officeIncome->update($validated);

        return redirect()->route('office-incomes.index')
            ->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(OfficeIncome $officeIncome)
    {
        $officeIncome->delete();

        return redirect()->route('office-incomes.index')
            ->with('success', 'Pemasukan berhasil dihapus.');
    }
}
