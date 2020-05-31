<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class WishlistItem extends Model {
    use SoftDeletes;

    protected $table = 'wishlist_item';
    protected $fillable = [
        'user_id',
        'product_id',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        // 'user_id' => 'required',
        // 'product_id' => 'required'
    ];

    public $validationMessages = [
        // 'user_id.required' => 'O campo usuario é obrigatório.',
        // 'product_id.required' => 'O campo produto é obrigatório.'
    ];

    public function getWishlistItemByProductId($productId) {
        $wishlistItem = WishlistItem::where('product_id', $productId);

        if (Auth::user()) {
            $wishlistItem->where('user_id', Auth::user()->id);
        }

        $wishlistItem->first();

        return $wishlistItem;
    }

    public function getWishlistItemById($id) {
        return WishlistItem::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
    }

    public function getWishlistItemList($filter = null, $paginate = false, $limit = 15) {
        $wishlistItem = WishlistItem::join('product', 'product.id', '=', 'wishlist_item.product_id')
            ->select(
                'wishlist_item.id', 'wishlist_item.product_id', 'wishlist_item.user_id',
                'product.title as product_title', 'product.slug as product_slug'
            )
            ->orderBy('id', 'desc');

        $wishlistItem->where('user_id', Auth::user()->id);

        if ($filter != null && isset($filter['product_id']) && $filter['product_id'] != '') {
            $wishlistItem->where('product_id', $filter['product_id']);
        }

        if ($paginate === true) {
            $wishlistItem = $wishlistItem->paginate($limit);
        } else {
            $wishlistItem = $wishlistItem->get();
        }

        return $wishlistItem;
    }

    public function storeWishlistItem($productId) {
        $wishlistItem = new WishlistItem();

        $wishlistItem->product_id = $productId;
        $wishlistItem->user_id = Auth::user()->id;
        $wishlistItem->created_by = Auth::user()->id;

        $wishlistItem->save();

        return [
            'message' => 'Este produto foi adicionado a sua lista de favoritos.',
            'data' => $wishlistItem
        ];
    }

    public function deleteWishlistItemByProductId ($productId) {
        $wishlistItem = $this->getWishlistItemByProductId($productId);

        if ($wishlistItem == null) {
            throw new AppException('Cadastro [' . $productId . '] não encontrado.');
        }

        $wishlistItem->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $wishlistItem->save();

        return [
            'message' => 'Produto removido da lista de favoritos.'
        ];
    }

    public function deleteWishlistItem ($id) {
        $wishlistItem = $this->getWishlistItemById($id);

        if ($wishlistItem == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $wishlistItem->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $wishlistItem->save();

        return [
            'message' => 'Produto removido da lista de favoritos.'
        ];
    }
}
