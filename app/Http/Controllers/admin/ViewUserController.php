<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BorrowerProfile;
use Illuminate\Http\Request;

class ViewUserController extends Controller
{
    public function index()
    {
        $users = User::where('status', 'accepted')
            ->with('borrowerProfile')
            ->where('role_id', 2)
            ->get();

        return view('admin.user-management.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('borrowerProfile')->findOrFail($id);
        return view('admin.user-management.show', compact('user'));
    }
}
