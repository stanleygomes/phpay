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
        $cartHistory = CartHistory::orderBy('id', 'desc');

        if ($filter != null && isset($filter['cart_id']) && $filter['cart_id'] != '') {
            $cartHistory->where('cart_id', $filter['cart_id']);
        }

        if ($paginate === true) {
            $cartHistory = $cartHistory->paginate($limit);
        } else {
            $cartHistory = $cartHistory->get();
        }

        return $cartHistory;
    }

    public function storeCartHistory($cartId, $status, $description = null) {
        $cartHistory = new CartHistory();

        $cartHistory->cart_id = $cartId;
        $cartHistory->status = $status;
        $cartHistory->description = $description;

        $loggedUser = Auth::user();

        if ($loggedUser != null) {
            $cartHistory->created_by = $loggedUser->id;
        } else {
            $cartHistory->created_by = null;
        }

        $cartHistory->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $cartHistory
        ];
    }

    public function deleteCartHistory ($id) {
        $cartHistory = $this->getCartHistoryById($id);

        if ($cartHistory == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cartHistory->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cartHistory->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
