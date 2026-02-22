<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail; // Panggil Mailable yang kita buat tadi
use Illuminate\Support\Facades\Mail; // Panggil fungsi email
use Illuminate\Support\Str; // Panggil fungsi teks acak

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('email', $user->email)->first();

            if(!$finduser){
                $finduser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_google' => $user->id,
                    'password' => Hash::make('123456dummy'),
                ]);
            }

            // 1. Generate 6 digit OTP acak
            $otp = Str::upper(Str::random(6)); 
            
            // 2. Simpan OTP ke database user tersebut
            $finduser->update(['otp' => $otp]);

            // 3. Kirim email OTP ke Mailtrap
            Mail::to($finduser->email)->send(new OtpMail($otp));

            // 4. Simpan ID user di session sementara agar sistem tahu siapa yang sedang diverifikasi
            session(['otp_user_id' => $finduser->id]);

            // 5. Lempar ke halaman input OTP
            return redirect()->route('otp.view');

        } catch (Exception $e) {
            return redirect('login')->with('error', 'Gagal Login: ' . $e->getMessage());
        }
    }

    // Fungsi untuk mengecek apakah kode yang Pia masukkan benar
    public function verifyOtp(\Illuminate\Http\Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp == $request->otp) {
            // Kalau benar, login-kan user dan hapus OTP-nya (biar tidak bisa dipakai lagi)
            Auth::login($user);
            $user->update(['otp' => null]);
            return redirect()->intended('home');
        }

        // Kalau salah, balik lagi ke halaman OTP dengan pesan error
        return redirect()->back()->with('error', 'Kode OTP salah atau sudah kadaluwarsa.');
    }
}