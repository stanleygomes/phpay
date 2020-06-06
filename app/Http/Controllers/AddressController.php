<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Address;
use App\Helper\Helper;

class AddressController extends Controller {
    public function index() {
        try {
            $filter = Session::get('addressSearch');
            $addressInstance = new Address();
            $addresses = $addressInstance->getAddressList($filter, true);

            return view('address.index', [
                'addresses' => $addresses,
                'filter' => $filter
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('addressSearch', $filter);
            return Redirect::route('app.address.index');
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('address.form', [
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $addressInstance = new Address();
            $validateRequest = Helper::validateRequest($request, $addressInstance->validationRules, $addressInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $address = $addressInstance->storeAddress($request);

            return Redirect::route('app.address.index')
                ->with('status', $address['message']);
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $addressInstance = new Address();
            $address = $addressInstance->getAddressById($id);
            $modeEdit = true;

            return view('address.form', [
                'address' => $address,
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $addressInstance = new Address();
            $address = $addressInstance->updateAddress($request, $id);

            return Redirect::route('app.address.index')
                ->with('status', $address['message']);
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $addressInstance = new Address();
            $delete = $addressInstance->deleteAddress($id);

            return Redirect::route('app.address.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartAddressIndex() {
        try {
            $userLogged = Auth::user();

            if ($userLogged == null) {
                return Redirect::route('auth.login', ['redir' => '/cart/address']);
            }

            $addressInstance = new Address();
            $addresses = $addressInstance->getAddressList(null, false);

            return view('address.cart-index', [
                'addresses' => $addresses
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartAddressCreate() {
        try {
            $modeEdit = false;

            return view('address.cart-form', [
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.cart.cart')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartAddressStore(Request $request) {
        try {
            $addressInstance = new Address();
            $validateRequest = Helper::validateRequest($request, $addressInstance->validationRules, $addressInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $address = $addressInstance->storeAddress($request);

            return Redirect::route('website.cart.address')
                ->with('status', $address['message']);
        } catch (AppException $e) {
            return Redirect::route('website.cart.address')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartAddressEdit($id) {
        try {
            $addressInstance = new Address();
            $address = $addressInstance->getAddressById($id);
            $modeEdit = true;

            return view('address.cart-form', [
                'address' => $address,
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.cart.address')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartAddressUpdate(Request $request, $id) {
        try {
            $addressInstance = new Address();
            $address = $addressInstance->updateAddress($request, $id);

            return Redirect::route('website.cart.address')
                ->with('status', $address['message']);
        } catch (AppException $e) {
            return Redirect::route('website.cart.address')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
