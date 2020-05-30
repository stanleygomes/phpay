<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Featured;
use App\Helper\Helper;

class FeaturedController extends Controller {
    public function index() {
        try {
            $filter = Session::get('featuredSearch');
            $featuredInstance = new Featured();
            $featureds = $featuredInstance->getFeaturedList($filter, true);

            return view('featured.index', compact('featureds', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('featuredSearch', $filter);
            return Redirect::route('app.featured.index');
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('featured.form', compact('modeEdit'));
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $featuredInstance = new Featured();
            $validateRequest = Helper::validateRequest($request, $featuredInstance->validationRules, $featuredInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $featured = $featuredInstance->storeFeatured($request);

            return Redirect::route('app.featured.index')
                ->with('status', $featured['message']);
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $featuredInstance = new Featured();
            $featured = $featuredInstance->getFeaturedById($id);
            $modeEdit = true;

            return view('featured.form', compact('featured', 'modeEdit'));
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $featuredInstance = new Featured();
            $featured = $featuredInstance->updateFeatured($request, $id);

            return Redirect::route('app.featured.index')
                ->with('status', $featured['message']);
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $featuredInstance = new Featured();
            $delete = $featuredInstance->deleteFeatured($id);

            return Redirect::route('app.featured.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.featured.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
