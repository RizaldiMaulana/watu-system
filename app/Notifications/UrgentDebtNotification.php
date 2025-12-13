<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UrgentDebtNotification extends Notification
{
    use Queueable;

    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Peringatan Jatuh Tempo: Invoice #' . $this->purchase->invoice_number)
            ->line("Invoice #{$this->purchase->invoice_number} dari {$this->purchase->supplier->name} jatuh tempo.")
            ->line('Total Tagihan: Rp ' . number_format($this->purchase->total_amount, 0, ',', '.'))
            ->line('Tanggal Jatuh Tempo: ' . date('d/m/Y', strtotime($this->purchase->due_date)))
            ->action('Lihat Hutang', route('accounting.reports.accounts_payable'))
            ->line('Mohon segera proses pembayaran.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Jatuh Tempo Pembayaran',
            'message' => "Invoice {$this->purchase->invoice_number} ({$this->purchase->supplier->name}) jatuh tempo hari ini/besok! Total: Rp ".number_format($this->purchase->total_amount, 0, ',', '.'),
            'url' => route('accounting.reports.accounts_payable'),
            'icon' => 'clock',
            'color' => 'red',
        ];
    }
}
