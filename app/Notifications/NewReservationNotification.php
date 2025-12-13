<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reservasi Baru',
            'message' => "Reservasi dari {$this->reservation->customer_name} untuk {$this->reservation->pax} orang pada ".date('d/m H:i', strtotime($this->reservation->reservation_time)),
            'url' => route('reservations.index'),
            'icon' => 'calendar-check',
            'color' => 'blue',
        ];
    }
}
