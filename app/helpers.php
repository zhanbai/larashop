<?php

use Symfony\Component\HttpKernel\Exception\HttpException;

function error_response($statusCode, $message = null, $code = 0)
{
    throw new HttpException($statusCode, $message, null, [], $code);
}

function ngrok_url($routeName, $parameters = [])
{
    // 开发环境，并且配置了 NGROK_URL
    if (app()->environment('local') && $url = config('app.ngrok_url')) {
        // route() 函数第三个参数代表是否绝对路径
        return $url . route($routeName, $parameters, false);
    }

    return route($routeName, $parameters);
}
