<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification
{
    use Queueable;

    public function __construct(private LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $leaveRequest = $this->leaveRequest;

        return (new MailMessage)
            ->subject('Có đơn xin nghỉ phép mới cần kiểm tra')
            ->line(sprintf('Nhân sự: %s', $leaveRequest->full_name))
            ->line(sprintf('Thời gian nghỉ: %s - %s',
                optional($leaveRequest->start_date)->format('d/m/Y'),
                optional($leaveRequest->end_date)->format('d/m/Y')
            ))
            ->line(sprintf('Số ngày xin nghỉ: %s ngày', $leaveRequest->days_requested))
            ->line('Vui lòng truy cập hệ thống để kiểm tra và cập nhật tình trạng đơn.')
            ->action('Xem đơn nghỉ phép', route('leave-requests.index'));
    }
}
