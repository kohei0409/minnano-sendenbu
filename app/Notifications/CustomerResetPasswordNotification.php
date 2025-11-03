<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomerResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     */
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('customer.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('パスワードリセットのご案内')
            ->greeting('こんにちは、' . $notifiable->name . '様')
            ->line('パスワードリセットのリクエストを受け付けました。')
            ->line('以下のボタンをクリックして、新しいパスワードを設定してください。')
            ->action('パスワードをリセット', $url)
            ->line('このリンクは ' . config('auth.passwords.customers.expire') . ' 分後に無効になります。')
            ->line('パスワードリセットを要求していない場合は、このメールを無視してください。')
            ->salutation('よろしくお願いいたします、' . config('app.name'));
    }
}
