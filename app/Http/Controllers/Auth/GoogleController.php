<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Str; 

class GoogleController extends Controller
{
    public function redirectToGoogle() //melempar user dari web menuju server google
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() //mengembalikan data user
    {
        try {
            $user = Socialite::driver('google')->user(); 
            $finduser = User::where('email', $user->email)->first();//cek db, kalo blm ada maka pake create

            if(!$finduser){
                $finduser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_google' => $user->id,
                    'password' => Hash::make('123456dummy'),
                ]);
            }

            // generate 6 digit OTP acak
            $otp = Str::upper(Str::random(6)); 
            
            // simpan OTP ke db user tersebut
            $finduser->update(['otp' => $otp]);

            // kirim email OTP ke Mailtrap
            Mail::to($finduser->email)->send(new OtpMail($otp));

            // simpan ID user di session sementara agar sistem tahu siapa yang sedang diverifikasi
            session(['otp_user_id' => $finduser->id]);

            // 5. Lempar ke halaman input OTP
            return redirect()->route('otp.view');

        } catch (Exception $e) {
            return redirect('login')->with('error', 'Gagal Login: ' . $e->getMessage());
        }
    }

    // cek kode yg dimasukin udh bener/engga
    public function verifyOtp(\Illuminate\Http\Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp == $request->otp) {
            // klo bener, user login dan otpnya otomatis kehapus (sekali pake)
            Auth::login($user);
            $user->update(['otp' => null]);
            return redirect()->intended('home');
        }

        // kalo salah, balik lagi ke halaman otp dengan pesan error
        return redirect()->back()->with('error', 'Kode OTP salah atau sudah kadaluwarsa.');
    }
}