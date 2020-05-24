<?php

namespace App\Http\Controllers;

use Exception;
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

            return view('address.index', compact('addresses', 'filter'));
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('address.form', compact('modeEdit'));
        } catch (Exception $e) {
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

            $addressInstance = new Address();
            $address = $addressInstance->storeAddress($request);

            return Redirect::route('app.address.index')
                ->with('status', $address['message']);
        } catch (Exception $e) {
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

            return view('address.form', compact('address', 'modeEdit'));
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $addressInstance = new Address();
            $message = $addressInstance->deleteAddress($id);

            return Redirect::route('app.address.index')
                ->with('status', $message);
        } catch (Exception $e) {
            return Redirect::route('app.address.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
