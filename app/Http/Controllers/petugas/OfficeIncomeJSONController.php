<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\OfficeIncome;
use Illuminate\Http\Request;

class OfficeIncomeJSONController extends Controller
{
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
}
