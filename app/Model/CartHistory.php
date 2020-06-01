<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartHistory extends Model {
    use SoftDeletes;

    protected $table = 'cart_history';
    protected $fillable = [
        'cart_id',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getCartHistoryById($id) {
        return CartHistory::where('id', $id)
            ->first();
    }

    public function getCartHistoryList($filter = null, $paginate = false, $limit = 15) {
        $cart = CartHistory::orderBy('id', 'desc');

        if ($filter != null && isset($filter['cart_id']) && $filter['cart_id'] != '') {
            $cart->where('cart_id', $filter['cart_id']);
        }

        if ($paginate === true) {
            $cart = $cart->paginate($limit);
        } else {
            $cart = $cart->get();
        }

        return $cart;
    }

    public function storeCartHistory($cartId, $status) {
        $cart = new CartHistory();

        $cart->cart_id = $cartId;
        $cart->status = $status;

        $loggedUser = Auth::user();

        if ($loggedUser != null) {
            $cart->created_by = $loggedUser->id;
        } else {
            $cart->created_by = null;
        }

        $cart->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $cart
        ];
    }

    public function deleteCartHistory ($id) {
        $cart = $this->getCartHistoryById($id);

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
