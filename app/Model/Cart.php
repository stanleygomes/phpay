<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model {
    use SoftDeletes;

    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_phone',
        'address_id',
        'address_zipcode',
        'address_street',
        'address_number',
        'address_complement',
        'address_district',
        'address_city',
        'address_state',
        'payment_methods_available_id',
        'price_total',
        'last_status',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $cartStatus = [
        'CREATED' => 'Criado',
        'PAYMENT_PENDING' => 'Aguardando pagamento',
        'PAID' => 'Pago',
        'ENDED' => 'Finalizado'
    ];

    public function getCartById($id) {
        return Cart::where('id', $id)
            ->first();
    }

    public function getCartList($filter = null, $paginate = false, $limit = 15) {
        $cart = Cart::orderBy('id', 'desc');

        if ($filter != null && isset($filter['name']) && $filter['name'] != '') {
            $cart->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if ($paginate === true) {
            $cart = $cart->paginate($limit);
        } else {
            $cart = $cart->get();
        }

        return $cart;
    }

    public function getSessionCartId() {
        $cartSessionId = Session::get('cart_id');

        if ($cartSessionId === null) {
            $cart = $this->storeCart();
            $cartId = $cart['data']->id;

            Session::put('cart_id', $cartId);
            $cartSessionId = $cartId;
        }

        return $cartSessionId;
    }

    public function storeCart() {
        $cart = new Cart();

        $createdStatus = $this->cartStatus['CREATED'];

        $cart->address_id = null;
        $cart->price_total = 0;
        $cart->last_status = $createdStatus;

        $loggedUser = Auth::user();

        if ($loggedUser != null) {
            $cart->user_id = $loggedUser->id;
            $cart->created_by = $loggedUser->id;
        } else {
            $cart->user_id = null;
            $cart->created_by = null;
        }

        $cart->save();

        $cartHistoryInstance = new CartHistory();
        $cartHistoryInstance->storeCartHistory($cart->id, $createdStatus);

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $cart
        ];
    }

    public function updatePriceTotal($cartId) {
        $totalPrice = 0;
        $cartItemInstance = new CartItem();
        $filter = [
            'cart_item' => $cartId
        ];

        $cartItems = $cartItemInstance->getCartItemList($filter, false);

        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $totalPrice = $totalPrice + ($cartItem->product_price * $cartItem->qty);
        }

        $cart = $this->getCartById($cartId);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $cartId . '] não encontrado.');
        }

        $cart->price_total = $totalPrice;

        $cart->save();
    }

    public function updateCart($request, $id) {
        $cart = $this->getCartById($id);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cart->name = $request->name;

        $cart->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $cart
        ];
    }

    public function deleteCart ($id) {
        $cart = $this->getCartById($id);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cart->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cart->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
