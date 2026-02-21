<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    // Fungsi 1: Diarahkan ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Fungsi 2: Proses setelah login dari Google berhasil
    public function handleGoogleCallback()
{
    try {
        $user = Socialite::driver('google')->user();
        
        // Cari user berdasarkan email
        $finduser = User::where('email', $user->email)->first();

        if($finduser){
            Auth::login($finduser);
            return redirect()->intended('home');
        } else {
            // Buat user baru jika belum ada
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'id_google' => (string) $user->id, // Kita paksa jadi string
                'password' => bcrypt('123456dummy') // Gunakan bcrypt, bukan encrypt
            ]);

            Auth::login($newUser);
            return redirect()->intended('home');
        }

    } catch (Exception $e) {
        // Baris ini bakal kasih tau kita alasan sebenernya kenapa gagal
        dd($e->getMessage()); 
    }
}
}