<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Model\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AppController extends Controller {
    public function dashboard() {
        $userLogged = Auth::user();

        if ($userLogged->profile !== 'ADMIN') {
            return Redirect::route('app.cart.index');
        }

        $yearMonth = date('Y-m');
        $dateStart = $yearMonth . '01 00:00:00';
        $dateEnd = $yearMonth . '0' . date('t') . ' 23:59:59';
        $dateStartShow = '01' . date('/m/Y') . ' 00:00';
        $dateEndShow = date('t/m/Y') . ' 23:59';
        $monthName = Helper::getMonthByNum(date('m'));

        $cartInstance = new Cart();
        $carts = $cartInstance->getCartList(null, true, 5);
        $cartsResume = $cartInstance->getCartsResumeByYearMonth($dateStart, $dateEnd);
        $cartsByDay = $cartInstance->getCartsByDayChart($dateStart, $dateEnd);

        return view('app.dashboard', [
            'carts' => $carts,
            'cartsResume' => $cartsResume,
            'monthName' => $monthName,
            'dateStart' => $dateStartShow,
            'dateEnd' => $dateEndShow,
            'cartsByDay' => $cartsByDay
        ]);
    }

    public function config() {
        return view('app.config');
    }

    public function pageAbout() {
        return view('pages.about');
    }

    public function pagePrivacy() {
        return view('pages.privacy');
    }

    public function pageFaq() {
        return view('pages.faq');
    }

    public function pageReturning() {
        return view('pages.returning');
    }

    public function pageDelivery() {
        return view('pages.delivery');
    }
}
