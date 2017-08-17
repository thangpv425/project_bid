<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class ChangeEmailMailable extends Mailable {
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
        return $this->view('emails.change_email')
            ->with(compact('data'))
            ->subject('Change Email');
    }
}