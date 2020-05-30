<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model {
    use SoftDeletes;

    protected $table = 'product_review';
    protected $fillable = [
        'product_id',
        'cart_id',
        'description',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'evaluation' => 'required',
        'description' => 'required'
    ];

    public $validationMessages = [
        'evaluation.required' => 'O campo nota é obrigatório.',
        'description.required' => 'O campo descrição é obrigatório.'
    ];

    public function getProductReviewById($id) {
        return ProductReview::where('id', $id)
            ->first();
    }

    public function getProductReviewList($filter = null, $paginate = false, $limit = 15) {
        $productReview = ProductReview::orderBy('id', 'desc');

        if ($filter != null && isset($filter['product_id']) && $filter['product_id'] != '') {
            $productReview->where('product_id', $filter['product_id']);
        }

        if ($paginate === true) {
            $productReview = $productReview->paginate($limit);
        } else {
            $productReview = $productReview->get();
        }

        return $productReview;
    }

    public function storeProductReview($request) {
        $productReview = new ProductReview();

        $productReview->product_id = $request->product_id;
        $productReview->cart_id = $request->cart_id;
        $productReview->evaluation = $request->evaluation;
        $productReview->description = $request->description;
        $productReview->created_by = Auth::user()->id;

        $productReview->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $productReview
        ];
    }

    public function updateProductReview($request, $id) {
        $productReviewInstance = new ProductReview();
        $productReview = $productReviewInstance->getProductReviewById($id);

        if ($productReview == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productReview->evaluation = $request->evaluation;
        $productReview->description = $request->description;

        $productReview->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $productReview
        ];
    }

    public function deleteProductReview ($id) {
        $productReviewInstance = new ProductReview();
        $productReview = $productReviewInstance->getProductReviewById($id);

        if ($productReview == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productReview->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $productReview->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
