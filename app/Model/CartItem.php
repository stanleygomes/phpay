<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model {
    use SoftDeletes;

    protected $table = 'cart_item';
    protected $fillable = [
        'product_id',
        'product_code',
        'product_title',
        'product_description_short',
        'product_price',
        'product_photo_url',
        'category_id',
        'category_name',
        'qty',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $cartItemMaxProd = 10;

    public function getCartItemById($id) {
        return CartItem::where('id', $id)
            ->first();
    }

    public function getCartItemByProductId($cartId, $productId) {
        return CartItem::join('product', 'product.id', '=', 'cart_item.product_id')
            ->select(
                'cart_item.id', 'cart_item.product_id', 'cart_item.product_code', 'cart_item.product_title', 'cart_item.product_description_short',
                'cart_item.product_price', 'cart_item.product_photo_url', 'cart_item.category_id', 'cart_item.category_name',
                'cart_item.qty', 'product.stock_qty'
            )
            ->where('cart_item.product_id', $productId)
            ->where('cart_item.cart_id', $cartId)
            ->first();
    }

    public function getCartItemCount ($cartId) {
        return CartItem::where('cart_id', $cartId)
            ->sum('qty');
    }

    public function updateCartItemMaxQtyAvailable($cartItems) {
        for($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];

            if ($cartItem->stock_qty > $this->cartItemMaxProd) {
                $cartItem->stock_qty = $this->cartItemMaxProd;
            }
        }

        return $cartItems;
    }

    public function getCartItemList($filter = null, $paginate = false, $limit = 15) {
        $cartItem = CartItem::join('product', 'product.id', '=', 'cart_item.product_id')
            ->select(
                'cart_item.product_id', 'cart_item.product_code', 'cart_item.product_title', 'cart_item.product_description_short',
                'cart_item.product_price', 'cart_item.product_photo_url', 'cart_item.category_id', 'cart_item.category_name',
                'cart_item.qty', 'product.stock_qty'
            )
            ->orderBy('cart_item.id', 'desc');

        if ($filter != null && isset($filter['cart_id']) && $filter['cart_id'] != '') {
            $cartItem->where('cart_item.cart_id', $filter['cart_id']);
        }

        if ($paginate === true) {
            $cartItem = $cartItem->paginate($limit);
        } else {
            $cartItem = $cartItem->get();
        }

        return $cartItem;
    }

    public function addCartItem($cartId, $productId) {
        $cartItemExists = $this->getCartItemByProductId($cartId, $productId);

        if ($cartItemExists === null) {
            return $this->storeCartItem($cartId, $productId);
        } else {
            return $this->updateCartItem($cartId, $productId);
        }
    }

    public function storeCartItem($cartId, $productId) {
        $cartItem = new CartItem();

        $productInstance = new Product();
        $product = $productInstance->getProductById($productId);

        $categoryInstance = new Category();
        $category = $categoryInstance->getCategoryById($product->category_id);

        $cartItem->cart_id = $cartId;
        $cartItem->product_id = $product->id;
        $cartItem->product_code = $product->code;
        $cartItem->product_title = $product->title;
        $cartItem->product_description_short = $product->description_short;
        $cartItem->product_price = $product->price;
        $cartItem->product_photo_url = $product->photo_main_url;
        $cartItem->category_id = $category->id;
        $cartItem->category_name = $category->name;
        $cartItem->qty = 1;

        $loggedUser = Auth::user();

        if ($loggedUser != null) {
            $cartItem->created_by = $loggedUser->id;
        } else {
            $cartItem->created_by = null;
        }

        $cartItem->save();

        return [
            'message' => 'Produto adicionado no carrinho.',
            'data' => $cartItem
        ];
    }

    public function updateCartItem($cartId, $productId, $qty = null) {
        $cartItemInstance = new CartItem();
        $cartItem = $cartItemInstance->getCartItemByProductId($cartId, $productId);

        if ($cartItem == null) {
            throw new AppException('Cadastro [' . $productId . '] não encontrado.');
        }

        $cartInstance = new Cart();
        $cartInstance->updatePriceTotal($cartId);

        if ($qty == null) {
            if ($cartItem->qty < $cartItem->stock_qty && $cartItem->qty < $this->cartItemMaxProd) {
                $cartItem->qty = $cartItem->qty + 1;
            } else {
                throw new AppException('Produto [' . $productId . '] sem estoque no momento.');
            }
        } else {
            if ($qty < $cartItem->stock_qty && $qty < $this->cartItemMaxProd) {
                $cartItem->qty = $qty;
            } else {
                throw new AppException('Produto [' . $productId . '] sem estoque no momento.');
            }
        }

        $cartItem->save();

        return [
            'message' => 'Carrinho atualizado.',
            'data' => $cartItem
        ];
    }

    public function deleteCartItemByProductId ($cartId, $productId) {
        $cartItem = $this->getCartItemByProductId($cartId, $productId);

        if ($cartItem == null) {
            throw new AppException('Cadastro [' . $productId . '] não encontrado.');
        }

        $cartItem->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cartItem->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }

    public function deleteCartItem ($id) {
        $cartItem = $this->getCartItemById($id);

        if ($cartItem == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cartItem->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cartItem->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
