<?php

namespace App\Listeners;

use App\Events\TestEmailEvent;
use App\Mail\TestEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class TestEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TestEmailEvent  $event
     * @return void
     */
    public function handle(TestEmailEvent $event)
    {
        Mail::to($event->emailData['email'])->send(new TestEmail($event->emailData));
    }
}
