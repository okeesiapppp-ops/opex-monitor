<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class ReminderEtaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $unitList;
    public $namaPlanner;
    public function __construct($unitList, $namaPlanner)
    {
        $this->unitList = $unitList;
        $this->namaPlanner = $namaPlanner;
    }
    public function build()
    {
        return $this->subject("[Reminder] Backlog Part Mendekati Plan Date | OPEX Monitor")
                    ->view('emails.reminder-eta');
    }
}