<?php

namespace App\Helper;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Category;
use App\Model\Store;

class Helper {
    private static $messages = [
        'pb-br' => [
            'USER_LIST_ERROR' => 'Erro ao listar usuários',
        ],
        'en' => [
        ]
    ];

    public static function getStoreData() {
        $storeId = 1;
        $storeInstance = new Store();
        $store = $storeInstance->getStoreById($storeId);
        $store->formatAddress = $store->street . ', '. $store->number . ($store->complement != null ? ' - ' . $store->complement : '') . ' - ' . $store->district . ' - ' . $store->city . '/' . $store->state;

        return $store;
    }

    public static function truncateText($text, $limit) {
        $count = strlen($text);

        if ($count > $limit) {
            return substr($text, 0, $limit) . '...';
        }

        return $text;
    }

    public static function getCategoryList() {
        try {
            $categoryInstance = new Category();
            return $categoryInstance->getCategoryList(null, false);
        } catch (AppException $e) {
            Log::error($e);
        }
    }

    public static function convertMoneyFromBRtoUS($money) {
        $money = str_replace('.', '', $money);
        $money = str_replace(',', '.', $money);

        return floatval($money);
    }

    public static function convertMoneyFromUStoBR($money) {
        return number_format($money, 2, ',', '.');
    }

    public static function validateRequest($request, $rules, $messages) {
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

    public function uploadFile($file, $folder) {
        // TODO: move to cloud storage
        $yearMonth = date('Y/m/');
        $originalName = $file->getClientOriginalName();
        $originalExtension = $file->getClientOriginalExtension();

        $fileName = md5(date('YmdHis') . $originalName) . '.' . $originalExtension;
        $directory = public_path() . '/uploads/' . $folder . '/' . $yearMonth;

        if (is_dir($directory) === false) {
            File::makeDirectory($directory, 0777, true);
        }

        // Image::make($request->file($fieldName))->resize(300, 200)->save('foo.jpg');

        $file->move($directory, $fileName);

        return $yearMonth.$fileName;
	}
}
