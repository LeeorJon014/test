<?php

namespace App\Notifications;

use App\Helpers\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class VerifyEmail extends Notification
{
    use Queueable;
    public $password;
    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
       
    }
   
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return (new MailMessage)
            ->from('precup@example.com', 'PRECUP')
            ->subject('[PRECUP - EMAIL VERIFICATION]')
            ->greeting('Greetings!')
            ->line('The Email you provided: ' . $notifiable->email)
            ->line('The Password provided: ' . $this->password)
            ->line('Please click the button below to verify your email address before you can access the system.')
            ->action('Verify Email Address', $verificationUrl);
    }
    

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
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
