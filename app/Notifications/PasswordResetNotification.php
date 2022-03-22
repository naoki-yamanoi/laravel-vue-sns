<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\BareMail;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail)
    {
        $this->token = $token;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->mail
            // 送信元メアド, 名前
            ->from(config('mail.from.address'), config('mail.from.name'))
            // 送信先となるUserモデル->email
            ->to($notifiable->email)
            // メールの件名
            ->subject('[memo]パスワード再設定')
            // resources/views/emailsのpassword_reset.blade.phpがテンプレートとして使用される。
            ->text('emails.password_reset')
            ->with([
                'url' => route('password.reset', [
                    'token' => $this->token,
                    // クエリストリング(Query String)としてURLに付加される
                    'email' => $notifiable->email,
                ]),
                // パスワード設定画面へのURLの有効期限(単位は分)
                'count' => config(
                    'auth.passwords.' .
                        config('auth.defaults.passwords') .
                        '.expire'
                ),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
