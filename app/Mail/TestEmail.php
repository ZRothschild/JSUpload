<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $emailData;
    /**
     * Create a new message instance.
     * @param array $emailData
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email')
            ->with([
                'email' => $this->emailData['email'],
                'code' => $this->emailData['message'],
                'text'=>$this->emailData['text'],
            ])->subject($this->emailData['title']);
    }
}
