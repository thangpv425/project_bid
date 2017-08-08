<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    //mail data
    protected $data;

    //mail config
    protected $config;

    /**
     * ForgotPasswordMailable constructor.
     * @param array $data data send to mail
     * @param array $config mail config
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
