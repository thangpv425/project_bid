<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(array $data) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

    }

}
