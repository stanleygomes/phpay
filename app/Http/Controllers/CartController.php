<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\CartItem;

class CartController extends Controller {
    public function cart() {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getSessionCartId();
            $cart = $cartInstance->getCartById($cartId);

            $filter = [
                'cart_id' => $cartId
            ];

            $cartInstance = new CartItem();
            $cartItems = $cartInstance->getCartItemList($filter, true);

            DB::commit();

            return view('cart.cart', [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'filter' => $filter
            ]);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        try {
            $filter = Session::get('cartSearch');
            $cartInstance = new Cart();
            $carts = $cartInstance->getCartList($filter, true);

            return view('cart.index', [
                'carts' => $carts,
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
            Session::put('cartSearch', $filter);
            return Redirect::route('app.cart.index');
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('cart.form', [
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function addProduct($productId) {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getSessionCartId();

            $cartItemInstance = new CartItem();
            $cartItem = $cartItemInstance->addCartItem($cartId, $productId);

            DB::commit();

            return Redirect::back()
                ->with('status', $cartItem['message']);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function updateProduct(Request $request, $productId) {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getSessionCartId();

            $cartItemInstance = new CartItem();
            $cartItem = $cartItemInstance->updateCartItem($cartId, $productId, $request->qty);

            DB::commit();

            return Redirect::back()
                ->with('status', $cartItem['message']);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function deleteProduct(Request $request, $productId) {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getSessionCartId();

            $cartItemInstance = new CartItem();
            $cartItem = $cartItemInstance->deleteCartItemByProductId($cartId, $productId);

            DB::commit();

            return Redirect::back()
                ->with('status', $cartItem['message']);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($id);
            $modeEdit = true;

            return view('cart.form', [
                'cart' => $cart,
                'modeEdit' => $modeEdit
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $cartInstance = new Cart();
            $cart = $cartInstance->updateCart($request, $id);

            return Redirect::route('app.cart.index')
                ->with('status', $cart['message']);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $cartInstance = new Cart();
            $delete = $cartInstance->deleteCart($id);

            return Redirect::route('app.cart.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
