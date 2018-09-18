<?php

namespace App\Jobs;

use App\Events\TestEmailEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $emailData;

    public $tries = 5;
    /**
     * Create a new job instance.
     * @param  array $emailData
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('test=>'.now());
        event(new TestEmailEvent($this->emailData));
    }

    /**
     * 执行失败的任务。
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        $msg = $exception->getMessage();
        Log::info('msgmsg');
        Log::info($msg);
        Log::info('msgmsg');
        // 给用户发送失败的通知等等...
    }
}
