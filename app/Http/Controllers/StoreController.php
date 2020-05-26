<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Store;
use App\Helper\Helper;

class StoreController extends Controller {
    public function index() {
        try {
            $filter = Session::get('storeSearch');
            $storeInstance = new Store();
            $stores = $storeInstance->getStoreList($filter, true);

            return view('store.index', compact('stores', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('storeSearch', $filter);

            return Redirect::route('app.store.index');
        } catch (AppException $e) {
            return Redirect::route('app.store.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('store.form', compact('modeEdit'));
        } catch (AppException $e) {
            return Redirect::route('app.store.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $storeInstance = new Store();
            $validateRequest = Helper::validateRequest($request, $storeInstance->validationRules, $storeInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $storeInstance = new Store();
            $store = $storeInstance->storeStore($request);

            return Redirect::route('app.store.index')
                ->with('status', $store['message']);
        } catch (AppException $e) {
            return Redirect::route('app.store.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $storeInstance = new Store();
            $store = $storeInstance->getStoreById($id);
            $modeEdit = true;

            if ($store == null) {
                return Redirect::route('app.dashboard')
                    ->withErrors('Loja não cadastrada.')
                    ->withInput();
            }

            return view('store.form', compact('store', 'modeEdit'));
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
            // return Redirect::route('app.store.index')
            //     ->withErrors($e->getMessage())
            //     ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $storeInstance = new Store();
            $store = $storeInstance->updateStore($request, $id);

            return Redirect::back()
                ->with('status', $store['message']);

            // return Redirect::route('app.store.index')
            //     ->with('status', $store['message']);
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
            // return Redirect::route('app.store.index')
            //     ->withErrors($e->getMessage())
            //     ->withInput();
        }
    }

    public function delete($id) {
        try {
            $storeInstance = new Store();
            $message = $storeInstance->deleteStore($id);

            return Redirect::route('app.store.index')
                ->with('status', $message);
        } catch (AppException $e) {
            return Redirect::route('app.store.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
