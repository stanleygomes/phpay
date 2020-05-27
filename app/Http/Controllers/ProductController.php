<?php

namespace App\Http\Controllers;

use App\Model\Featured;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller {
    public function home() {
        try {
            $filter = [
                'order_position' => true
            ];

            $featuredInstance = new Featured();
            $featureds = $featuredInstance->getFeaturedList($filter, false);

            return view('product.home', compact('featureds'));
        } catch (AppException $e) {
            return Redirect::route('website.contact')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function show() {
        return view('product.show');
    }
}
