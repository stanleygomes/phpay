<?php

namespace App\Http\Controllers;

class ProductController extends Controller {
    public function home() {
        return view('product.home');
    }

    public function show() {
        return view('product.show');
    }
}
