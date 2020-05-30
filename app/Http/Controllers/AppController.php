<?php

namespace App\Http\Controllers;

class AppController extends Controller {
    public function dashboard() {
        return view('app.dashboard');
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
