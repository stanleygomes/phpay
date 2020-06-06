<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class CartReview extends Model {
    use SoftDeletes;

    protected $table = 'cart_review';
    protected $fillable = [
        'cart_id',
        'evaluation',
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

    public function getCartReviewById($id) {
        return CartReview::where('id', $id)
            ->first();
    }

    public function getCartReviewList($filter = null, $paginate = false, $limit = 15) {
        $cartReview = CartReview::join('cart', 'cart.id', '=', 'cart_review.cart_id')
            ->select(
                'cart_review.id', 'cart_review.cart_id', 'cart_review.evaluation', 'cart_review.description', 'cart_review.created_at',
                'cart.user_name', 'cart.user_email', 'cart.user_phone'
            )
            ->orderBy('id', 'desc');

        if ($filter != null && isset($filter['user_name']) && $filter['user_name'] != '') {
            $cartReview->where('cart.user_name', 'like', '%' . $filter['user_name'] . '%');
        }

        if ($paginate === true) {
            $cartReview = $cartReview->paginate($limit);
        } else {
            $cartReview = $cartReview->get();
        }

        return $cartReview;
    }

    public function storeCartReview($request) {
        $cartReview = new CartReview();

        $cartReview->user_id = Auth::user()->id;
        $cartReview->cart_id = $request->cart_id;
        $cartReview->evaluation = $request->evaluation;
        $cartReview->description = $request->description;
        $cartReview->created_by = Auth::user()->id;

        $cartReview->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $cartReview
        ];
    }

    public function deleteCartReview ($id) {
        $cartReviewInstance = new CartReview();
        $cartReview = $cartReviewInstance->getCartReviewById($id);

        if ($cartReview == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cartReview->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cartReview->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }

    public function sendMail($cartReview) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => env('MAIL_TO_ADDRESS'),
            'to_name' => env('MAIL_TO_NAME')
        ];
        $subject = 'Avaliação de compra no site';
        $template = 'cart-review';
        $data = $cartReview;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }
}
