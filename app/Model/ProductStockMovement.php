<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStockMovement extends Model {
    use SoftDeletes;

    protected $table = 'product_stock_movement';
    protected $fillable = [
        'product_id',
        'value',
        'action',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'value' => 'required',
        'action' => 'required'
    ];

    public $validationMessages = [
        'value.required' => 'O campo valor é obrigatório.',
        'action.required' => 'O campo ação é obrigatório.'
    ];

    public function getProductStockMovementById($id) {
        return ProductStockMovement::where('id', $id)
            ->first();
    }

    public function getProductStockMovementList($filter = null, $paginate = false, $limit = 15) {
        $productReview = ProductStockMovement::orderBy('id', 'desc');

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

    public function storeProductStockMovement($productId, $value, $action) {
        $productReview = new ProductStockMovement();

        $productReview->product_id = $productId;
        $productReview->value = $value;
        $productReview->action = $action;
        $productReview->created_by = Auth::user()->id;

        $productReview->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $productReview
        ];
    }

    public function updateProductStockMovement($request, $id) {
        $productReviewInstance = new ProductStockMovement();
        $productReview = $productReviewInstance->getProductStockMovementById($id);

        if ($productReview == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productReview->value = $request->value;
        $productReview->action = $request->action;

        $productReview->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $productReview
        ];
    }

    public function deleteProductStockMovement ($id) {
        $productReviewInstance = new ProductStockMovement();
        $productReview = $productReviewInstance->getProductStockMovementById($id);

        if ($productReview == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productReview->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $productReview->save();

        return 'Cadastro deletado com sucesso.';
    }
}
