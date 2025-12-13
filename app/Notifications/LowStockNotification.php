<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Peringatan Stok Menipis: ' . $this->item->name)
            ->line("Stok untuk item {$this->item->name} menipis.")
            ->line("Sisa Stok: {$this->item->stock} {$this->item->unit}")
            ->line("Minimum Stok: {$this->item->minimum_stock} {$this->item->unit}")
            ->action('Lihat Laporan Stok', route('reports.stock'))
            ->line('Mohon segera lakukan restock.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Stok Menipis',
            'message' => "Stok {$this->item->name} sisa {$this->item->stock} {$this->item->unit}.",
            'url' => route('reports.stock'),
            'icon' => 'exclamation-triangle',
            'color' => 'red',
        ];
    }
}
