<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class ProductPhoto extends Model {
    use SoftDeletes;

    protected $table = 'product_photo';
    protected $fillable = [
        'product_id',
        'photo_url',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'photo_url' => 'required'
    ];

    public $validationMessages = [
        'photo_url.required' => 'O campo foto é obrigatório.'
    ];

    public function getProductPhotoById($id) {
        return ProductPhoto::where('id', $id)
            ->first();
    }

    public function getProductPhotoList($filter = null, $paginate = false, $limit = 15) {
        $productPhoto = ProductPhoto::orderBy('id', 'desc');

        if ($filter != null && isset($filter['product_id']) && $filter['product_id'] != '') {
            $productPhoto->where('product_id', $filter['product_id']);
        }

        if ($paginate === true) {
            $productPhoto = $productPhoto->paginate($limit);
        } else {
            $productPhoto = $productPhoto->get();
        }

        return $productPhoto;
    }

    public function storeProductPhoto($image, $productId) {
        $productPhoto = new ProductPhoto();

        $productPhoto->product_id = $productId;
        $productPhoto->created_by = Auth::user()->id;

        $fileManagerInstance = new Helper();
        $productPhoto->photo_url = $fileManagerInstance->uploadFile($image, 'product');

        $productPhoto->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $productPhoto
        ];
    }

    public function updateProductPhoto($request, $id) {
        $productPhotoInstance = new ProductPhoto();
        $productPhoto = $productPhotoInstance->getProductPhotoById($id);

        if ($productPhoto == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $fileManagerInstance = new Helper();
        $productPhoto->photo_url = $fileManagerInstance->uploadFile($request, 'product');

        $productPhoto->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $productPhoto
        ];
    }

    public function deleteProductPhoto ($id) {
        $productPhotoInstance = new ProductPhoto();
        $productPhoto = $productPhotoInstance->getProductPhotoById($id);

        if ($productPhoto == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productPhoto->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $productPhoto->save();

        return 'Cadastro deletado com sucesso.';
    }
}
