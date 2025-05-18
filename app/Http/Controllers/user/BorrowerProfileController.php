<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\BorrowerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BorrowerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = BorrowerProfile::with('user')
            ->where('user_id', auth()->id())
            ->get();

        $hasProfile = $profiles->isNotEmpty();

        return view('user.borrower-profiles.index', compact('profiles', 'hasProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'pekerjaan' => 'required|string',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_diri' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_simpanan' => 'required|in:pokok,wajib,sukarela',
            'tabungan_pokok' => 'required|numeric|min:0',
        ]);

        $fotoKtpPath = $request->file('foto_ktp')->store('public/borrower_profiles');
        $fotoKkPath = $request->file('foto_kk')->store('public/borrower_profiles');
        $fotoDiriPath = $request->file('foto_diri')->store('public/borrower_profiles');

        BorrowerProfile::create([
            'user_id' => $request->user_id,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'pekerjaan' => $request->pekerjaan,
            'foto_ktp' => str_replace('public/', '', $fotoKtpPath),
            'foto_kk' => str_replace('public/', '', $fotoKkPath),
            'foto_diri' => str_replace('public/', '', $fotoDiriPath),
            'jenis_simpanan' => $request->jenis_simpanan,
            'tabungan_pokok' => $request->tabungan_pokok,
        ]);

        return redirect()->route('borrower-profiles.index')->with('success', 'Profil peminjam berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowerProfile $borrowerProfile)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'pekerjaan' => 'required|string',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_simpanan' => 'required|in:pokok,wajib,sukarela',
            'tabungan_pokok' => 'required|numeric|min:0',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'pekerjaan' => $request->pekerjaan,
            'jenis_simpanan' => $request->jenis_simpanan,
            'tabungan_pokok' => $request->tabungan_pokok,
        ];

        if ($request->hasFile('foto_ktp')) {
            Storage::delete('public/' . $borrowerProfile->foto_ktp);
            $fotoKtpPath = $request->file('foto_ktp')->store('public/borrower_profiles');
            $data['foto_ktp'] = str_replace('public/', '', $fotoKtpPath);
        }

        if ($request->hasFile('foto_kk')) {
            Storage::delete('public/' . $borrowerProfile->foto_kk);
            $fotoKkPath = $request->file('foto_kk')->store('public/borrower_profiles');
            $data['foto_kk'] = str_replace('public/', '', $fotoKkPath);
        }

        if ($request->hasFile('foto_diri')) {
            Storage::delete('public/' . $borrowerProfile->foto_diri);
            $fotoDiriPath = $request->file('foto_diri')->store('public/borrower_profiles');
            $data['foto_diri'] = str_replace('public/', '', $fotoDiriPath);
        }

        $borrowerProfile->update($data);

        return redirect()->route('borrower-profiles.index')->with('success', 'Profil peminjam berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowerProfile $borrowerProfile)
    {
        Storage::delete([
            'public/' . $borrowerProfile->foto_ktp,
            'public/' . $borrowerProfile->foto_kk,
            'public/' . $borrowerProfile->foto_diri,
        ]);

        $borrowerProfile->delete();

        return redirect()->route('borrower-profiles.index')->with('success', 'Profil peminjam berhasil dihapus.');
    }
}
