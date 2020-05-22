<?php

namespace App\Helper;

class LangUtil {
    public static function getMessage($key) {
        $defaultLocale = env('APP_LOCALE');
        return $this->messages[$defaultLocale][$key];
    }

    private static $messages = [
        'pb-br' => [
            'USER_LIST_ERROR' => 'Erro ao listar usuários',
        ],
        'en' => [

        ]
    ];
}
