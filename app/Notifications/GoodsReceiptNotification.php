<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GoodsReceiptNotification extends Notification
{
    use Queueable;

    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengiriman Barang',
            'message' => "Pengiriman PO {$this->purchase->invoice_number} dari {$this->purchase->supplier->name} telah diterima.",
            'url' => route('goods-receipt.index'),
            'icon' => 'truck',
            'color' => 'blue',
        ];
    }
}
