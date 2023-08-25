<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function jsonResult(bool $result, string $message, string $status, $data = [], $exception = '',): array
    {
        $json = [];
        $json['result'] = $result;
        $json['message'] = $message;
        $json['data'] = $data;
        $json['exception'] = $exception;
        $json['status'] = $status;
        return $json;
    }
}