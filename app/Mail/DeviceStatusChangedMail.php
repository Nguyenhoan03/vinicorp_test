<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class DeviceStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $deviceName;
    public $status;

    public function __construct($user, $deviceName, $status)
    {
        $this->user = $user;
        $this->deviceName = $deviceName;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Thiết bị' . $this->deviceName . ' đã được cập nhật trạng thái thành ' . $this->status)
            ->view('emails.device_status_changed');
    }
}