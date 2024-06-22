<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $email;

    public function __construct($email ,$token)
    {
        $this->email = $email;
        $this->token = $token;
    }


    public function build()
    {
        return $this->subject('Password reset')->view('auth.passwords.resetPassword' , ['email' => $this->email ,'token' => $this->token]);
    }
}
