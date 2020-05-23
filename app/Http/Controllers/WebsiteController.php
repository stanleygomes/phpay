<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller {
    public function home() {
        return view('home');
    }

    public function about() {
        return view('pages.about');
    }

    public function privacy() {
        return view('pages.privacy');
    }

    public function faq() {
        return view('pages.faq');
    }

    public function returning() {
        return view('pages.returning');
    }

    public function delivery() {
        return view('pages.delivery');
    }
}
