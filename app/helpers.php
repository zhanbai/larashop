<?php

use Symfony\Component\HttpKernel\Exception\HttpException;

function error_response($statusCode, $message = null, $code = 0)
{
    throw new HttpException($statusCode, $message, null, [], $code);
}
