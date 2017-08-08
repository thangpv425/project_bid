<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailManager {
    public function send($to, Mailable $content) {
        Mail::to($to)->send($content);
    }
}