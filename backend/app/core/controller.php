<?php

class Controller
{
    protected function success($data = null, string $message = 'OK', int $code = 200): void
    {
        Response::success($data, $message, $code);
    }

    protected function error(string $message = 'Error', int $code = 400, $data = null): void
    {
        Response::error($message, $code, $data);
    }
}