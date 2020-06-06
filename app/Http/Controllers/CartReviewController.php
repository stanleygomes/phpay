<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\CartReview;
use App\Helper\Helper;

class CartReviewController extends Controller {
    public function index() {
        try {
            $filter = Session::get('cartReviewSearch');
            $cartReviewInstance = new CartReview();
            $cartReviews = $cartReviewInstance->getCartReviewList($filter, true);

            return view('cartReview.index', [
                'cartReviews' => $cartReviews,
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
            Session::put('cartReviewSearch', $filter);
            return Redirect::route('app.cartReview.index');
        } catch (AppException $e) {
            return Redirect::route('app.cartReview.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create($cartId) {
        try {
            $modeEdit = false;
            $userLogged = Auth::user();

            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($cartId);

            if ($cart == null || $cart->user_id !== $userLogged->id) {
                return Redirect::route('app.cartReview.index')
                    ->with('status', 'Não é possível avaliar o pedido #' . $cartId . '.');
            }

            return view('cartReview.form', [
                'modeEdit' => $modeEdit,
                'cartId' => $cartId
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cartReview.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $cartReviewInstance = new CartReview();
            $validateRequest = Helper::validateRequest($request, $cartReviewInstance->validationRules, $cartReviewInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $cartReview = $cartReviewInstance->storeCartReview($request);

            $cartReviewInstance = new CartReview();
            $cartReviewInstance->sendMail($cartReview['data']);

            return Redirect::route('app.cartReview.index')
                ->with('status', $cartReview['message']);
        } catch (AppException $e) {
            return Redirect::route('app.cartReview.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $cartReviewInstance = new CartReview();
            $delete = $cartReviewInstance->deleteCartReview($id);

            return Redirect::route('app.cartReview.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.cartReview.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
