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
        ini_set("memory_limit","-1");
        try{
            $emptyNum = [];
            if (empty($request->input('fileName'))) {
                $emptyNum[$request->input('num')] = $request->input('num');
            }else{
                $redisExist = Redis::ZRANGEBYSCORE(md5($request->input('fileName')),$request->input('num'),$request->input('num'),'WITHSCORES');
                if (empty($redisExist)){
                    $setRes = Redis::zadd(md5($request->input('fileName')),$request->input('num'),file_get_contents('php://input', 'rb'));
                    Log::info('num',[md5($request->input('fileName')),$request->input('num'),$setRes]);
                }
                $count = Redis::ZCOUNT(md5($request->input('fileName')),0,$request->input('num'));
                if ($count > 0){
                    $countNum = 0;
                    while ($countNum <= $request->input('num')){
                        $rang = Redis::ZRANGEBYSCORE(md5($request->input('fileName')),$countNum,$countNum,'WITHSCORES');
                        if (empty($rang)){
                            $emptyNum[$countNum] = $countNum;
                        }
                        $countNum++;
                    }
                }
            }
            if (!empty($emptyNum)){
                return response()->json(['status'=>2,'message' => "部分上传失败继续上传",'data'=>$emptyNum]);
            }
            $total = Redis::ZCOUNT(md5($request->input('fileName')),0,$request->input('numSize'));
            if ($total === (int)$request->input('numSize')) {
                $bool = file_exists(storage_path('app/public/resources/'.md5($request->input('fileName')).$request->input('extend')));
                $rang = Redis::zRange(md5($request->input('fileName')),0,-1,'WITHSCORES');
                $switch = Redis::exists('aaa');
                if (empty($switch)) Redis::set('aaa',1);
                $getSwitch = Redis::get('aaa');
                Log::info('bbbbbbbbbbbbbbbbb',[$bool,$getSwitch,$total,$request->input('numSize')]);
                if (!$bool && $getSwitch){
                    Redis::set('aaa',0);
//                    Redis::EXEC();
                    Log::info('aaaaaaaaaa',[11111111111111111111111111]);
                    foreach ($rang as  $key => $value){
                        Log::info("tick=>",[$value]);
                        file_put_contents(storage_path('app/public/resources/'.md5($request->input('fileName')).$request->input('extend')),$key, FILE_APPEND);
                    }
                }
                return response()->json(['status'=>3,'message' => '全部上传完成','data'=>[]]);
            }
            return response()->json(['status'=>1,'message' => 'successful','data'=>[]]);
        }catch (\Exception $exception){
            return response()->json(['status'=>0,'message' => $exception->getMessage(),'data'=>[]]);
        }

    }

    public function testRedis()
    {

//        $switch = Redis::exists('switch');
//        if (empty($switch)) Redis::set('switch',1);
//        Redis::WATCH('switch');
//        Redis::MULTI();
//        Redis::set('switch',0);
//        Redis::EXEC();
//        $getSwitch = Redis::get('switch');
//        dump($getSwitch);
//        die;
        ini_set("memory_limit","-1");
//        $bool = file_exists('../storage/app/public/resources/.env');
//        dd($bool);
        $setResa = Redis::zadd('g',1,'a');
        $setResb = Redis::zadd('g',4,'b');
        $setResc = Redis::zadd('g',3,'c');
        $setResc = Redis::zadd('g',2,'a');
        $setResc = Redis::zadd('g',5,'c');
        $rang = Redis::zRange('e10adc3949ba59abbe56e057f20f883e',0,-1);
        $aa = json_encode($rang);
        var_dump($aa);
        die;
//        foreach ($rang as $key => $value){
//            dump($key);
//        }
//
//        $count = Redis::ZCOUNT('99b2d8aa01d81b61cbe8cf5cf7287650',0,400);
//        dump($count);
//        die;
//        $rang = Redis::ZRANGEBYSCORE('de1bce2a848dac3673fa713677c5ff4b','0','120','WITHSCORES');
//        dd($rang);
        $count = Redis::ZCOUNT('de1bce2a848dac3673fa713677c5ff4b',0,400);
        dump($count);

        $clear = Redis::flushall();
        dump($clear);
    }
}
