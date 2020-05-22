<?php

namespace App\Helper;

class HttpUtil {
    // HTTP STATUS LIST: https://httpstatuses.com
    public static function httpResponse($message, $code = 200) {
        return [
            'code' => $code,
            'message' => $message
        ];
    }
}
