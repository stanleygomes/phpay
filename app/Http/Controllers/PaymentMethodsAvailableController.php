<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\PaymentMethodsAvailable;
use App\Helper\Helper;

class PaymentMethodsAvailableController extends Controller {
    public function index() {
        try {
            $filter = Session::get('paymentMethodsAvailableSearch');
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $paymentMethodsAvailables = $paymentMethodsAvailableInstance->getPaymentMethodsAvailableList($filter, true);

            return view('paymentMethodsAvailable.index', compact('paymentMethodsAvailables', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('paymentMethodsAvailableSearch', $filter);
            return Redirect::route('app.paymentMethodsAvailable.index');
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $paymentGatewayMethodsList = $paymentMethodsAvailableInstance->getGatewaysMethods();

            return view('paymentMethodsAvailable.form', compact('modeEdit', 'paymentGatewayMethodsList'));
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $validateRequest = Helper::validateRequest($request, $paymentMethodsAvailableInstance->validationRules, $paymentMethodsAvailableInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $paymentMethodsAvailable = $paymentMethodsAvailableInstance->storePaymentMethodsAvailable($request);

            return Redirect::route('app.paymentMethodsAvailable.index')
                ->with('status', $paymentMethodsAvailable['message']);
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $paymentMethodsAvailable = $paymentMethodsAvailableInstance->getPaymentMethodsAvailableById($id);
            $modeEdit = true;
            $paymentGatewayMethodsList = $paymentMethodsAvailableInstance->getGatewaysMethods();

            return view('paymentMethodsAvailable.form', compact('paymentMethodsAvailable', 'modeEdit', 'paymentGatewayMethodsList'));
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $paymentMethodsAvailable = $paymentMethodsAvailableInstance->updatePaymentMethodsAvailable($request, $id);

            return Redirect::route('app.paymentMethodsAvailable.index')
                ->with('status', $paymentMethodsAvailable['message']);
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $delete = $paymentMethodsAvailableInstance->deletePaymentMethodsAvailable($id);

            return Redirect::route('app.paymentMethodsAvailable.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.paymentMethodsAvailable.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
