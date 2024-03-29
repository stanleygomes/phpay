<?php

namespace App\Http\Controllers;

use Exception;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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
use App\Model\TransactionFee;
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

            $transactionFeeInstance = new TransactionFee();
            $transactionFees = $transactionFeeInstance->getTransactionFeeList($filter, false);

            $transactionInstance = new Transaction();
            $transactions = $transactionInstance->getTransactionList($filter, false);

            return view('cart.show', [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'cartHistories' => $cartHistories,
                'canceledStatus' => $canceledStatus,
                'transactions' => $transactions,
                'transactionFees' => $transactionFees
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
            $cartHistoryInstance->storeCartHistory($cart->id, $pendingStatus['code'], $pendingStatus['description']);

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

            $cartInstance->sendMailCartOrdered($cart, $cartItems);

            // payment request
            $paymentMercadoPagoInstance = new PaymentMercadoPago();
            $preference = $paymentMercadoPagoInstance->getPreference($cart, $cartItems, $paymentMethodsAvailable);

            $preferenceId = $preference->id;
            $preferenceUrl = $preference->init_point;

            $cartInstance->updateCartStatus($cart->id, $pendingStatus, true, $preferenceId);

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
            $cartInstance = new Cart();
            $cart = $cartInstance->getCartByPreferenceId($request->preference_id);

            if ($cart === null) {
                $message = 'Pedido inválido.';
                return Redirect::route('website.home')
                ->withErrors($message)
                    ->withInput();
            }

            if ($status === 'failure') {
                $lastStatus = $cartInstance->getCartStatusByCode('CANCELED');

                $cartHistoryInstance = new CartHistory();
                $cartHistoryInstance->storeCartHistory($cart->id, $lastStatus['code'], $lastStatus['description']);
                $cartInstance->updateCartStatus($cart->id, $lastStatus['code']);
            }

            DB::commit();

            return Redirect::route('website.cart.callbackPage', [
                'status' => $status,
                'cart_id' => $cart->id
            ]);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function callbackPage($status, $cartId) {
        try {
            $cartInstance = new Cart();
            $cart = $cartInstance->getCartById($cartId);
            $routeCartShow = route('auth.login') . '?redir=' . route('app.cart.show', ['id' => $cart->id]);

            if ($status === 'success' || $status === 'pending') {
                $statusCart = $cartInstance->getCartStatusByCode('PAYMENT_PENDING');
            } else if ($status === 'failure') {
                $statusCart = $cartInstance->getCartStatusByCode('CANCELED');
            }

            return view('cart.callback', [
                'status' => $status,
                'description' => $statusCart['description'],
                'cart' => $cart,
                'routeCartShow' => $routeCartShow
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.cart.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function updateStatus(Request $request, $gateway) {
        // disabled CSRF-TOKEN
        // app/Http/Middleware/VerifyCsrfToken.php

        try {
            DB::beginTransaction();
            if ($gateway === 'mercadopago') {
                $paymentMercadoPagoInstance = new PaymentMercadoPago();
                return $paymentData = $paymentMercadoPagoInstance->getPaymentStatusFromApi($request);

                $cartInstance = new Cart();
                $cart = $cartInstance->getCartByPreferenceId($paymentData['preference_id']);

                if ($cart == null) {
                    throw new AppException('Carrinho nao encontrado para o preference_id: ' . $paymentData['preference_id']);
                }

                $paymentMethodsAvailableInstance = new PaymentMethodsAvailable();
                $gateway = $paymentMethodsAvailableInstance->getGateway('MERCADO_PAGO');

                $paymentData['cart_id'] = $cart->id;
                $paymentData['gateway'] = $gateway['id'];

                $transactionInstance = new Transaction();
                $transaction = $transactionInstance->storeTransaction($paymentData);

                $transactionFeeInstance = new TransactionFee();
                $transactionFeeInstance->storeTransactionFeeList($paymentData['fees'], $transaction['data']->id, $cart->id);

                $cartHistoryInstance = new CartHistory();
                $cartHistoryInstance->storeCartHistory($cart->id, $paymentData['status'], $paymentData['status_description']);

                $cartInstance->updateCartStatus($cart->id, $paymentData['status']);

                if ($paymentData['status'] === 'approved') {
                    $cartInstance->updateStockOnPaymentAproved($cart->id);
                    $cartInstance->sendMailPaid($cart);
                }

                DB::commit();
            }
        } catch (AppException $e) {
            DB::rollBack();
            return $e;
            Log::error($e);
        }
    }
}
