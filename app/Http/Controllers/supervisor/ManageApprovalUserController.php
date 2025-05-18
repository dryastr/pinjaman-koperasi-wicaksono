<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use App\Models\BorrowerProfile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManageApprovalUserController extends Controller
{
    /**
     * Display a listing of pending borrower profiles.
     */
    public function index()
    {
        $users = BorrowerProfile::with('user')
            ->where('status', 'pending')
            ->whereHas('user', function ($query) {
                $query->where('role_id', Role::where('name', 'user')->first()->id);
            })
            ->get();

        return view('supervisor.manage-approval-users.index', compact('users'));
    }

    /**
     * Update the borrower profile status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $profile = BorrowerProfile::findOrFail($id);

        Log::info('Updating borrower profile status', [
            'borrower_profile_id' => $profile->id,
            'user_id' => $profile->user_id,
            'old_status' => $profile->status,
            'new_status' => $request->status,
            'time' => now()
        ]);

        $profile->status = $request->status;
        $profile->save();

        Log::info('Borrower profile status updated', [
            'borrower_profile_id' => $profile->id,
            'status' => $profile->status,
            'time' => now()
        ]);

        return redirect()->route('manage-approval-users.index')
            ->with('success', 'Status profil peminjam berhasil diperbarui.');
    }

    /**
     * Remove the specified borrower profile.
     */
    public function destroy($id)
    {
        $profile = BorrowerProfile::findOrFail($id);
        $profile->delete();

        return redirect()->route('manage-approval-users.index')
            ->with('success', 'Profil peminjam berhasil dihapus.');
    }
}
