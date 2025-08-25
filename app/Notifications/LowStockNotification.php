<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;
    protected $lowStockProducts;

    public function __construct($product = null, $lowStockProducts = null)
    {
        $this->product = $product;
        $this->lowStockProducts = $lowStockProducts;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        if ($this->product) {
            // Single product notification
            return (new MailMessage())
                ->subject('⚠️ Peringatan Stok Menipis - ' . $this->product->name)
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Kami ingin memberitahu Anda bahwa stok produk berikut sudah menipis:')
                ->line('**Produk:** ' . $this->product->name)
                ->line('**Kode:** ' . $this->product->code)
                ->line('**Stok Saat Ini:** ' . $this->product->stock . ' ' . $this->product->unit)
                ->line('**Minimum Stok:** ' . $this->product->min_stock . ' ' . $this->product->unit)
                ->action('Lihat Detail Produk', url('/products/' . $this->product->id))
                ->line('Segera lakukan restocking untuk menghindari kehabisan stok.')
                ->salutation('Salam, Tim ' . config('app.name'));
        } else {
            // Multiple products notification
            $mail = (new MailMessage())
                ->subject('⚠️ ' . count($this->lowStockProducts) . ' Produk Memerlukan Restocking')
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Berikut adalah daftar produk yang memerlukan perhatian Anda:');

            foreach ($this->lowStockProducts as $product) {
                $mail->line('');
                $mail->line('📦 **' . $product->name . '** (Kode: ' . $product->code . ')');
                $mail->line('   Stok: ' . $product->stock . ' ' . $product->unit . ' | Min: ' . $product->min_stock . ' ' . $product->unit);
            }

            return $mail->action('Lihat Semua Produk', url('/products'))
                ->line('Total ' . count($this->lowStockProducts) . ' produk memerlukan restocking segera.')
                ->salutation('Salam, Tim ' . config('app.name'));
        }
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product ? $this->product->id : null,
            'product_name' => $this->product ? $this->product->name : null,
            'current_stock' => $this->product ? $this->product->stock : null,
            'min_stock' => $this->product ? $this->product->min_stock : null,
            'count' => $this->lowStockProducts ? count($this->lowStockProducts) : 1,
            'message' => $this->product
                ? 'Stok ' . $this->product->name . ' tersisa ' . $this->product->stock . ' ' . $this->product->unit
                : count($this->lowStockProducts) . ' produk memerlukan restocking'
        ];
    }
}
