<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp; // Variabel untuk menyimpan kode OTP

    // Fungsi ini untuk menerima kode OTP saat dikirim nanti
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // Fungsi ini untuk mengatur tampilan emailnya
    public function build()
    {
        return $this->subject('Kode Verifikasi OTP Koleksi Buku')
                    ->html("<h2>Halo!</h2><p>Kode OTP Anda adalah: <b>{$this->otp}</b></p>");
    }
}