<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManageApprovalUserController extends Controller
{
    /**
     * Display a listing of pending users.
     */
    public function index()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        })
            ->where('status', 'pending')
            ->get();

        return view('supervisor.manage-approval-users.index', compact('users'));
    }

    /**
     * Update the user status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $user = User::findOrFail($id);

        \Log::info('Updating user status', [
            'user_id' => $user->id,
            'old_status' => $user->status,
            'new_status' => $request->status,
            'time' => now()
        ]);

        $user->status = $request->status;
        $user->save();

        \Log::info('User status updated', [
            'user_id' => $user->id,
            'status' => $user->status,
            'time' => now()
        ]);

        return redirect()->route('manage-approval-users.index')
            ->with('success', 'Status user berhasil diperbarui.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('manage-approval-users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
