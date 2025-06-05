<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BorrowerProfile;
use Symfony\Component\HttpFoundation\Response;

class CheckBorrowerProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $borrowerProfile = BorrowerProfile::where('user_id', $user->id)->first();

        if (!$borrowerProfile || $borrowerProfile->status === 'pending') {
            return redirect()->route('borrower-profiles.index')
                ->with('error', 'Anda harus melengkapi profil peminjam terlebih dahulu sebelum mengakses fitur ini.');
        }

        return $next($request);
    }
}
