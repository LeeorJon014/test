<?php

namespace App\Listeners;

use App\Events\SendEmailVerification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use App\Helpers\Common;

class SendEmailVerificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(SendEmailVerification $event)
    {
        $user = $event->user;
        $verificationUrl = $this->verificationUrl($user);
        
        Mail::to($user->email)->send(new VerifyEmail($user->email, $verificationUrl, $event->password));
    }

/**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    private function verificationUrl($notifiable)
    {
        $email = $notifiable->email;

        $now = Carbon::now();

        // Convert the Carbon instance to a Unix timestamp
        $timestamp = $now->timestamp;

        // Convert the Unix timestamp to a string using strtotime
        $created_at = $timestamp;
        $hash = Common::createTokenHash($email, $created_at);

        return url('verify-email/' . $email . '/' . $created_at . '/' . $hash);
    }
}