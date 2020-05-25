<?php

namespace App\Http\Controllers;

class AppController extends Controller {
    public function dashboard() {
        return view('app.dashboard');
    }

    public function config() {
        return view('app.config');
    }
}
