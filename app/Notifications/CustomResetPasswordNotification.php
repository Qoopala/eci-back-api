<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function toMail($notifiable)
    {
        $frontendUrl = env('FRONT_APP_URL');
        
        return (new MailMessage)
            ->subject('Restablecimiento de contraseña')
            ->line('Has recibido este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer contraseña', url("$frontendUrl/auth/recoverypass?token={$this->token}&email={$notifiable->email}"))
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}