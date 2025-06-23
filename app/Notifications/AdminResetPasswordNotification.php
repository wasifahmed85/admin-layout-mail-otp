<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AdminResetPasswordNotification extends Notification
{
    public $token;
    public function __construct( $token)
    {
        $this->token = $token;
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
        $resetUrl = URL::route('admin.password.reset', ['token' => $this->token, 'email' => $notifiable->email]);

        Log::info('Reset URL: ' . $resetUrl);
        return (new MailMessage)
            ->subject('Admin Password Reset')
            ->line('Click the button below to reset your password:')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
