<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    //mail data
    protected $data;

    /**
     * ForgotPasswordMailable constructor.
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.forgot_password')
            ->with('link',$this->data['link'])
            ->subject('Reset Password');
    }

}
