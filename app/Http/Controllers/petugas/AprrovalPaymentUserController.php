<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\LoanPayment;
use Illuminate\Http\Request;

class AprrovalPaymentUserController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index()
    {
        $payments = LoanPayment::with(['user', 'loanApplication'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.approval-payments.index', compact('payments'));
    }

    /**
     * Update the payment status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        \Log::info('Update Payment Request:', [
            'payment_id' => $id,
            'status' => $request->status,
            'user' => auth()->user()->id
        ]);

        $payment = LoanPayment::findOrFail($id);

        $payment->status = $request->status;
        $payment->save();

        return redirect()->route('approval-payments.index')
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(LoanPayment $payment)
    {
        if ($payment->bukti_pembayaran) {
            \Storage::delete('public/' . $payment->bukti_pembayaran);
        }

        $payment->delete();

        return redirect()->route('approval-payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
