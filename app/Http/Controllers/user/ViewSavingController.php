<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewSavingController extends Controller
{
    public function index()
    {
        $savings = Saving::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();

        return view('user.savings-history.index', compact('savings'));
    }
}
