<?php

namespace App\Helper;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Category;
use App\Model\Store;
use App\Model\Cart;
use App\Model\CartItem;

class Helper {
    public static function splitName($fullName) {
        $split = explode(' ', $fullName);
        $firstName = array_shift($split);
        $lastName = implode(' ', $split);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
    }

    public static function splitPhone($phone) {
        if ($phone == null) {
            return null;
        }

        return [
            'area_code' => substr($phone, 1, 2),
            'number' => trim(substr($phone, 4, 100))
        ];
    }

    public static function getDayFromDateUS($date) {
        $dateArray = explode('-', $date);
        if ($dateArray != null && count($dateArray) === 3) {
            return $dateArray[2];
        } else {
            return null;
        }
    }

    public static function getMonthByNum($num) {
        $monthList = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        $month = intval($num);

        return $monthList[$month - 1];
    }

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

    public static function sumCartItem($cartItems) {
        $totalPrice = 0;

        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $totalPrice = $totalPrice + ($cartItem->product_price * $cartItem->qty);
        }

        return $totalPrice;
    }

    public static function statusColorCart($status) {
        $cartInstance = new Cart();
        return $cartInstance->statusColorCart($status);
    }

    public static function formatCartId($cartId) {
        return str_pad($cartId, 6, '0', STR_PAD_LEFT);
    }

    public static function getCategoryList() {
        try {
            $categoryInstance = new Category();
            return $categoryInstance->getCategoryList(null, false);
        } catch (AppException $e) {
            Log::error($e);
        }
    }

    public static function getSessionCartId() {
        $cartSessionId = Session::get('cart_id');
        return $cartSessionId;
    }

    public static function putSessionCartId($cartId) {
        Session::put('cart_id', $cartId);
        return $cartId;
    }

    public static function removeSessionCartId() {
        Session::remove('cart_id');
    }

    public static function getCartItemCount() {
        try {
            $cartId = Helper::getSessionCartId();

            $cartItemInstance = new CartItem();
            return $cartItemInstance->getCartItemCount($cartId);
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

    public function sendMail($param, $data, $template, $subject) {
        Mail::send('mail.' . $template, ['data' => $data], function ($m) use ($param, $subject) {
            $m->from($param['from_email'], $param['from_name']);

            if(isset($param['cc_email']) && isset($param['cc_name'])) {
                $m->cc($param['cc_email'], $param['cc_name']);
            }

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
