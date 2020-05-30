<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class Product extends Model {
    use SoftDeletes;

    protected $table = 'product';
    protected $fillable = [
        'category_id',
        'code',
        'featured',
        'slug',
        'title',
        'price',
        'description',
        'description_short',
        'stock_qty',
        'evaluation_rate',
        'photo_main_url',
        'more_details',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'code' => 'required',
        'title' => 'required',
        'price' => 'required',
        'description_short' => 'required'
    ];

    public $validationMessages = [
        'code.required' => 'O campo código é obrigatório.',
        'title.required' => 'O campo título é obrigatório.',
        'price.required' => 'O campo preço é obrigatório.',
        'description_short.required' => 'O campo descrição curta é obrigatório.',
    ];

    public function getProductById($id) {
        return Product::where('id', $id)
            ->first();
    }

    public function getProductList($filter = null, $paginate = false, $limit = 15) {
        $product = Product::join('category', 'category.id', '=', 'product.category_id')
            ->select(
                'product.id', 'product.category_id', 'product.code', 'product.title', 'product.price', 'product.description',
                'product.description_short', 'product.slug', 'product.stock_qty', 'product.evaluation_rate', 'product.photo_main_url',
                'product.more_details',
                'category.name as category_name'
            );

        if ($filter != null && isset($filter['title']) && $filter['title'] != '') {
            $product->where('title', 'like', '%' . $filter['title'] . '%');
        }

        if ($filter != null && isset($filter['order_by']) && $filter['order_by'] > 0) {
            if (isset($filter['order_by_direction']) == null) {
                $filter['order_by_direction'] = 'desc';
            }

            $product->orderBy($filter['order_by'], $filter['order_by_direction']);
        } else {
            $product->orderBy('id', 'desc');
        }

        if ($filter != null && isset($filter['limit']) && $filter['limit'] > 0) {
            $product->limit($filter['limit']);
        }

        if ($filter != null && isset($filter['featured']) && $filter['featured'] != '') {
            $product->where('featured', $filter['featured']);
        }

        if ($filter != null && isset($filter['category_id']) && $filter['category_id'] != '') {
            $product->where('category_id', $filter['category_id']);
        }

        if ($paginate === true) {
            $product = $product->paginate($limit);
        } else {
            $product = $product->get();
        }

        return $product;
    }

    public function storeProduct($request) {
        $product = new Product();

        $product->category_id = $request->category_id;
        $product->code = $request->code;
        $product->featured = $request->featured;
        $product->title = $request->title;
        $product->price = Helper::convertMoneyFromBRtoUS($request->price);
        $product->slug = Helper::slugify($request->title);
        $product->description = $request->description;
        $product->description_short = $request->description_short;
        $product->more_details = $request->more_details;
        $product->created_by = Auth::user()->id;
        $product->stock_qty = $request->stock_qty;

        // starter rating: 0
        $product->evaluation_rate = 0;

        $product->save();

        $productId = $product->id;
        $mainPhoto = null;

        // save photos
        if ($request->file('photos') != null) {
            foreach ($request->file('photos') as $key => $file) {
                $productPhotoInstance = new ProductPhoto();
                $productPhoto = $productPhotoInstance->storeProductPhoto($file, $productId);

                if ($key === 0) {
                    $mainPhoto = $productPhoto['data']->photo_url;
                }
            }
        }

        // start stock
        $productStockMovementInstance = new ProductStockMovement();
        $productStockMovementInstance->storeProductStockMovement($product->id, $request->stock_qty, 'ADD');

        // main product photo
        Product::where('id', $product->id)
            ->update([
                'photo_main_url' => $mainPhoto
            ]);

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $product
        ];
    }

    public function updateProduct($request, $id) {
        $productInstance = new Product();
        $product = $productInstance->getProductById($id);

        if ($product == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $oldStock = $product->stock_qty;
        $value = $request->stock_qty - $oldStock;

        if ($value !== 0) {
            $action = $value > 0 ? 'ADD' : 'REMOVE';
            $productStockMovementInstance = new ProductStockMovement();
            $productStockMovementInstance->storeProductStockMovement($product->id, $request->stock_qty, $action);
        }

        $product->category_id = $request->category_id;
        $product->code = $request->code;
        $product->featured = $request->featured;
        $product->stock_qty = $request->stock_qty;
        $product->title = $request->title;
        $product->price = Helper::convertMoneyFromBRtoUS($request->price);
        $product->slug = Helper::slugify($request->title);
        $product->description = $request->description;
        $product->description_short = $request->description_short;
        $product->more_details = $request->more_details;

        // save new photos
        if ($request->file('photos') != null) {
            foreach ($request->file('photos') as $key => $file) {
                $productPhotoInstance = new ProductPhoto();
                $productPhotoInstance->storeProductPhoto($file, $product->id);
            }
        }

        $product->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $product
        ];
    }

    public function setPhotoMain($productId, $photoId) {
        $productInstance = new Product();
        $product = $productInstance->getProductById($productId);

        if ($product == null) {
            throw new AppException('Cadastro [' . $productId . '] não encontrado.');
        }

        $productPhotoInstance = new ProductPhoto();
        $productPhoto = $productPhotoInstance->getProductPhotoById($photoId);

        $product->photo_main_url = $productPhoto->photo_url;
        $product->save();

        return [
            'message' => 'Foto definida como principal com sucesso.'
        ];
    }

    public function deleteProduct ($id) {
        $productInstance = new Product();
        $product = $productInstance->getProductById($id);

        if ($product == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $product->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $product->save();

        return 'Cadastro deletado com sucesso.';
    }
}
