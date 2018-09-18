<?php

namespace App\Http\Controllers\FrontEnd;

use App\Events\TestEmailEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;

class TestController extends Controller
{
    public function sendEmail()
    {

        $emailData = [
            'email' => '873908960@qq.com',
            'message' => '发达国家肯定会打开嘎多思考了房间爱的快乐发送的咖啡机阿卡丽',
            'text' => '发达国家思考了房间爱的快乐发送的咖啡机阿卡丽',
            'title' => '测试邮箱',
        ];
        SendEmailJob::dispatch($emailData)->onConnection('database');
        $this->dispatch(new SendEmailJob($emailData));
        dump(now());
        dump(11111111111111);
    }


    public function sendEmailOne()
    {
        $emailData = [
            'email' => '873908960@qq.com',
            'message' => '发达国家肯定会打开嘎多思考了房间爱的快乐发送的咖啡机阿卡丽',
            'text' => '发达国家思考了房间爱的快乐发送的咖啡机阿卡丽',
            'title' => '测试邮箱',
        ];
        event(new TestEmailEvent($emailData));

        dump(11111111223334);
    }
}
