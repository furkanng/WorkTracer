<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $oldStatus;
    public $newStatus;

    public function __construct(Task $task, $oldStatus, $newStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        return $this->subject('Görev Durumu Güncellendi')
                    ->markdown('emails.tasks.status-updated');
    }
} 