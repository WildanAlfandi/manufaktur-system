<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class DailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage())
            ->subject('📊 Laporan Harian - ' . Carbon::now()->format('d M Y'))
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Berikut adalah ringkasan aktivitas hari ini:');

        // Summary Stats
        $mail->line('');
        $mail->line('**📈 RINGKASAN HARI INI**');
        $mail->line('• Total Transaksi: ' . $this->reportData['total_transactions']);
        $mail->line('• Barang Masuk: ' . $this->reportData['total_in'] . ' transaksi');
        $mail->line('• Barang Keluar: ' . $this->reportData['total_out'] . ' transaksi');
        $mail->line('• Nilai Transaksi: Rp ' . number_format($this->reportData['total_value'], 0, ',', '.'));

        // Low Stock Alert
        if (count($this->reportData['low_stock_products']) > 0) {
            $mail->line('');
            $mail->line('**⚠️ PRODUK STOK MENIPIS**');
            foreach ($this->reportData['low_stock_products'] as $product) {
                $mail->line('• ' . $product->name . ' - Sisa: ' . $product->stock . ' ' . $product->unit);
            }
        }

        // Top Products
        if (count($this->reportData['top_products']) > 0) {
            $mail->line('');
            $mail->line('**🏆 TOP 5 PRODUK HARI INI**');
            foreach ($this->reportData['top_products'] as $index => $product) {
                $mail->line(($index + 1) . '. ' . $product->name . ' - ' . $product->total_quantity . ' ' . $product->unit);
            }
        }

        return $mail->action('Lihat Dashboard', url('/dashboard'))
            ->line('Laporan lengkap dapat dilihat di sistem.')
            ->salutation('Salam, Tim ' . config('app.name'));
    }
}
