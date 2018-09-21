<?php

namespace App\Http\Controllers\FrontEnd;

use App\Events\TestEmailEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

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


    public function upLoad(Request $request)
    {
        try{
            $emptyNum = [];
//            Redis::set($request->input('num'),file_get_contents('php://input', 'rb'));
            $setResa = Redis::zadd(md5($request->input('fileName')),$request->input('num'),$request->input('num'));
            $count = Redis::ZCOUNT(md5($request->input('fileName')),0,$request->input('numSize'));
            if ($count > 0){
                $countNum = 0;
                while ($countNum <= $request->input('num')){
                    $rang = Redis::ZRANGEBYSCORE(md5($request->input('fileName')),$countNum,$countNum,'WITHSCORES');
                    if (empty($rang)){
                        $emptyNum[] = $countNum;
                        Log::info('empty =>'.$countNum,$emptyNum);
                    }
                    $countNum++;
                }
            }
            Log::info('num',[md5($request->input('fileName')),$request->input('num'),$setResa]);
            if (!empty($emptyNum)){
                return response()->json(['status'=>2,'message' => "empty",'data'=>$emptyNum]);
            }
            return response()->json(['status'=>1,'message' => 'successful','data'=>[]]);
           //file_put_contents(storage_path('app/public/resources/'.md5($request->input('fileName')).$request->input('extend')),$img, FILE_APPEND);
        }catch (\Exception $exception){
            return response()->json(['status'=>1,'message' => $exception->getMessage()]);
        }

    }

    public function testRedis()
    {
//        $setResa = Redis::zadd('g',1,'a');
//        $setResb = Redis::zadd('g',2,'b');
//        $setResc = Redis::zadd('g',3,'c');
//        $rang = Redis::zRange('b03c628af6e36de51263e25a7dbe1c37',0,-1,'WITHSCORES');
//        $rang = Redis::ZRANGEBYSCORE('b03c628af6e36de51263e25a7dbe1c37','120','120','WITHSCORES');
        $count = Redis::ZCOUNT('9d1124dbb7f18b18fc801963d5b2760f',0,120);
        dump($count);
    }
}
