<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewWebOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesanan Online Baru',
            'message' => "Pesanan #{$this->order->invoice_number} dari {$this->order->customer_name}.",
            'url' => route('web-orders.index'),
            'icon' => 'shopping-cart',
            'color' => 'green',
        ];
    }
}
