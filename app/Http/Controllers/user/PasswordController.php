<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function edit()
    {
        if (Auth::user()->role->name !== 'user') {
            abort(403, 'Unauthorized');
        }

        return view('user.change-password');
    }

    public function update(Request $request)
    {
        if (Auth::user()->role->name !== 'user') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('user.password.edit')->with('success', 'Password berhasil diubah.');
    }
}
