<?php

namespace App\Helper;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class Helper {
    private static $messages = [
        'pb-br' => [
            'USER_LIST_ERROR' => 'Erro ao listar usuários',
        ],
        'en' => [
        ]
    ];

    public static function validateRequest ($request, $rules, $messages) {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }

        return null;
    }

    public static function getMessage($key) {
        $defaultLocale = env('APP_LOCALE');
        return $this->messages[$defaultLocale][$key];
    }

    public function sendMail($param, $data, $template, $subject) {
        Mail::send('mail.' . $template, ['data' => $data], function ($m) use ($param, $subject) {
            $m->from($param['from_email'], $param['from_name']);
            $m->to($param['to_email'], $param['to_name'])
                ->subject($subject . ' - ' . env('APP_NAME'));
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

    public function uploadFile($request, $fieldName, $folder) {
        // TODO: move to cloud storage
	    if ($request->hasFile($fieldName)) {
            $yearMonth = date('y/m');
            $file = $request->file($fieldName);
            $originalName = $file->getClientOriginalName();
            $originalExtension = $file->getClientOriginalExtension();

            $fileName = md5(date('YmdHis') . $originalName) . '.' . $originalExtension;
            $directory = public_path() . '/uploads/' . $folder . '/' . $yearMonth;

            if (is_dir($directory) === false) {
                File::makeDirectory($directory, 0777, true);
            }

            $file->move($directory, $fileName);

            return $yearMonth.$fileName;
        } else {
            return null;
        }
	}
}
