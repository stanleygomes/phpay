<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Model\Address;
use App\Model\Cart;
use App\Model\CartItem;
use App\Model\CartHistory;
use App\Model\PaymentMercadoPago;
use App\Model\PaymentMethodsAvailable;
use App\Model\Transaction;
use App\Model\User;

class CartController extends Controller {
    public function cart($finish = null) {
        try {
            DB::beginTransaction();

            $cartInstance = new Cart();
            $cartId = $cartInstance->getCreateSessionCartId();
            $cart = $cartInstance->getCartById($cartId);
            $cartInstance->updatePriceTotal($cartId);

            $filter = [
                'cart_id' => $cartId
            ];

            $cartItemInstance = new CartItem();
            $cartItems = $cartItemInstance->getCartItemList($filter, false);
            $cartItems = $cartItemInstance->updateCartItemMaxQtyAvailable($cartItems);

            DB::commit();

            $user = new User();

            if (count($cartItems) > 0) {
                $view = 'cart.cart';
            } else {
                $view = 'cart.empty';
            }

            return view($view, [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'user' => $user,
                'filter' => $filter,
                'finish' => $finish
            ]);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function addressConfirm($id) {
        try {
            $userLogged = Auth::user();

            if ($userLogged == null) {
                return Redirect::route('auth.login', ['redir' => '/cart/address/address']);
            }

            $addressInstance = new Address();
            $address = $addressInstance->getAddressById($id);

            $cartInstance = new Cart();
            $cart = $cartInstance->setAddress($address);

            return Redirect::route('website.cart.cart', ['finish' => 'finish'])
                ->with('status', $cart['message']);
        } catch (AppException $e) {
            return Redirect::route('website.cart.cart')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        try {
            $filter = Session::get('cartSearch');
            $cartInstance = new Cart();
            $filter['is_order_date'] = true;
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

    public function show($id) {
        try {
            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($id);

            $filter = [
                'cart_id' => $id
            ];

            $cartItemInstance = new CartItem();
            $cartItems = $cartItemInstance->getCartItemList($filter, false);

            $cartHistoryInstance = new CartHistory();
            $cartHistories = $cartHistoryInstance->getCartHistoryList($filter, false);

            $canceledStatus = $cartInstance->getCartStatusByCode('CANCELED');

            return view('cart.show', [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'cartHistories' => $cartHistories,
                'canceledStatus' => $canceledStatus
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cancel(Request $request, $id) {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $canceledStatus = $cartInstance->getCartStatusByCode('CANCELED');

            $cart = $cartInstance->getCartUserById($id);

            if ($cart == null) {
                $message = 'Pedido inválido.';
                return Redirect::back()
                    ->withErrors($message)
                    ->withInput();
            }

            $cartHistoryInstance = new CartHistory();
            $cartHistoryInstance->storeCartHistory($cart->id, $canceledStatus, $request->description);

            $cartInstance->updateCartStatus($cart->id, $canceledStatus);
            $cartInstance->sendMailCancel($cart, $request);
            $message = 'Solicitação de cancelamento realizada com sucesso';
            DB::commit();

            return Redirect::back()
                ->with('status', $message);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
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

    public function addProduct($productId) {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getCreateSessionCartId();

            $cartItemInstance = new CartItem();
            $cartItem = $cartItemInstance->addCartItem($cartId, $productId);

            DB::commit();

            return Redirect::route('website.cart.cart')
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
            $cartId = $cartInstance->getCreateSessionCartId();

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
            $cartId = $cartInstance->getCreateSessionCartId();

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

    public function payment() {
        try {
            DB::beginTransaction();
            $cartInstance = new Cart();
            $cartId = $cartInstance->getSessionCartId();
            $cart = $cartInstance->getCartById($cartId);
            $pendingStatus = $cartInstance->getCartStatusByCode('PAYMENT_PENDING');

            $cartHistoryInstance = new CartHistory();
            $cartHistoryInstance->storeCartHistory($cart->id, $pendingStatus, 'Pedido recebido');

            $cartInstance->updateCartStatus($cart->id, $pendingStatus, true);
            // $cartInstance->sendMailCartOrdered($cart);

            $filter = [
                'cart_id' => $cart->id
            ];

            $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
            $paymentMethodsAvailable = [
                'methods' => $paymentMethodsAvailableInstance->getAvailableMethodIdsFromGateway('MERCADO_PAGO'),
                'installments' => $cartInstance->getMaxInstallments()
            ];

            $cartItemInstance = new CartItem();
            $cartItems = $cartItemInstance->getCartItemList($filter, false);

            // payment request
            $paymentMercadoPagoInstance = new PaymentMercadoPago();
            $preference = $paymentMercadoPagoInstance->getPreference($cart, $cartItems, $paymentMethodsAvailable);

            $preferenceId = $preference->id;
            $preferenceUrl = $preference->init_point;

            // save transaction
            $transactionInstance = new Transaction();
            $transactionInstance->storeTransaction($cart->id, $preferenceId, $preferenceUrl);

            Helper::removeSessionCartId();

            DB::commit();

            return Redirect::to($preferenceUrl);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function callback(Request $request, $status) {
        try {
            DB::beginTransaction();
            $transactionInstance = new Transaction();
            $transaction = $transactionInstance->getCartIdByTransaction($request->preference_id);
            $statusHistory = $transactionInstance->getCartHistoryStatusByPaymentStatus($status);

            if ($transaction === null) {
                $message = 'Pedido inválido.';
                return Redirect::route('website.home')
                    ->withErrors($message)
                    ->withInput();
            }

            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($transaction->cart_id);

            $cartHistoryInstance = new CartHistory();
            $cartHistoryInstance->storeCartHistory($cart->id, $statusHistory['status'], $statusHistory['description']);

            $cartInstance->updateCartStatus($cart->id, $statusHistory['status']);

            DB::commit();

            return Redirect::route('website.cart.callbackPage', ['status' => $status, 'cart_id' => $cart->id]);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function callbackPage($status, $cartId) {
        try {
            $transactionInstance = new Transaction();
            $statusHistory = $transactionInstance->getCartHistoryStatusByPaymentStatus($status);

            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($cartId);

            return view('cart.callback', [
                'status' => $status,
                'description' => $statusHistory['description'],
                'cart' => $cart
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
