<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;
    protected $type;

    public function __construct(Transaction $transaction, $type = 'created')
    {
        $this->transaction = $transaction;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->transaction->type == 'in'
            ? '📥 Barang Masuk - ' . $this->transaction->invoice_number
            : '📤 Barang Keluar - ' . $this->transaction->invoice_number;

        $mail = (new MailMessage())
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Transaksi baru telah dicatat dalam sistem:')
            ->line('**Invoice:** ' . $this->transaction->invoice_number)
            ->line('**Tipe:** ' . ($this->transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar'))
            ->line('**Produk:** ' . $this->transaction->product->name)
            ->line('**Jumlah:** ' . $this->transaction->quantity . ' ' . $this->transaction->product->unit)
            ->line('**Harga:** Rp ' . number_format($this->transaction->price, 0, ',', '.'))
            ->line('**Total:** Rp ' . number_format($this->transaction->total, 0, ',', '.'))
            ->line('**Tanggal:** ' . $this->transaction->transaction_date->format('d M Y'));

        if ($this->transaction->type == 'in' && $this->transaction->supplier) {
            $mail->line('**Supplier:** ' . $this->transaction->supplier->name);
        } elseif ($this->transaction->type == 'out' && $this->transaction->customer) {
            $mail->line('**Customer:** ' . $this->transaction->customer->name);
        }

        if ($this->transaction->notes) {
            $mail->line('**Catatan:** ' . $this->transaction->notes);
        }

        return $mail->action('Lihat Detail Transaksi', url('/transactions/history'))
            ->line('Stok produk telah diperbarui secara otomatis.')
            ->salutation('Salam, Tim ' . config('app.name'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'transaction',
            'transaction_id' => $this->transaction->id,
            'invoice_number' => $this->transaction->invoice_number,
            'transaction_type' => $this->transaction->type,
            'product_name' => $this->transaction->product->name,
            'quantity' => $this->transaction->quantity,
            'total' => $this->transaction->total,
            'message' => ($this->transaction->type == 'in' ? 'Barang masuk' : 'Barang keluar') .
                        ' - ' . $this->transaction->product->name .
                        ' (' . $this->transaction->quantity . ' ' . $this->transaction->product->unit . ')'
        ];
    }
}
