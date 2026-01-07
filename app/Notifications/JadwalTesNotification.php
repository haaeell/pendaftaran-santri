<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JadwalTesNotification extends Notification
{
    use Queueable;

    protected $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Jadwal Tes Seleksi',
            'message' => 'Jadwal tes seleksi Anda telah ditentukan.',
            'waktu_mulai' => $this->jadwal->waktu_mulai,
            'waktu_selesai' => $this->jadwal->waktu_selesai,
        ];
    }
}
