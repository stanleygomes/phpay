<?php

namespace App\Helper;

use Illuminate\Support\Facades\Mail;

class Helper {
    private static $messages = [
        'pb-br' => [
            'USER_LIST_ERROR' => 'Erro ao listar usuários',
        ],
        'en' => [
        ]
    ];

    public static function getMessage($key) {
        $defaultLocale = env('APP_LOCALE');
        return $this->messages[$defaultLocale][$key];
    }

    public function sendMail($param, $data, $template, $subject) {
        Mail::send('mail.' . $template, ['data' => $data], function ($m) use ($param, $subject) {
            $m->from($param['from_email'], $param['from_name']);
            $m->to($param['to_email'], $param['to_name'])
                ->subject($subject . env('APP_NAME'));
        });
    }

    public static function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    // HTTP STATUS LIST: https://httpstatuses.com
    public static function httpResponse($message, $code = 200) {
        return [
            'code' => $code,
            'message' => $message
        ];
    }
}
