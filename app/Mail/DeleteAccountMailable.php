<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAccountMailable extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * ForgotPasswordMailable constructor.
     * @param array $data
     */
    public function __construct() {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.delete_account')
            ->subject('Delete account');
    }
}
