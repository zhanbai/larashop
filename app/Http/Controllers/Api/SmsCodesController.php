<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SmsCodeRequest;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class SmsCodesController extends Controller
{
    public function store(SmsCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                fail($message ?: '短信发送异常', 500);
            }
        }

        $key = 'smsCode_' . $phone;
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        Cache::put($key, $code, $expiredAt);

        return success();
    }
}
