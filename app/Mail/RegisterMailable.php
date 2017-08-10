<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterMailable extends Mailable
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
     * @return $this
     */
    public function build() {
        $data = $this->data;
        return $this->view('emails.register')
            ->with(compact('data'))
            ->subject('Register');
    }

}
