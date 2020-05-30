<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class ProductQuestion extends Model {
    use SoftDeletes;

    protected $table = 'product_question';
    protected $fillable = [
        'user_id',
        'product_id',
        'question',
        'answer',
        'answered_by',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'question' => 'required'
    ];

    public $validationMessages = [
        'question.required' => 'O campo pergunta é obrigatório.'
    ];

    public function getProductQuestionByProductId($productId) {
        return ProductQuestion::where('product_id', $productId)
            ->where('user_id', Auth::user()->id)
            ->first();
    }

    public function getProductQuestionById($id) {
        return ProductQuestion::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
    }

    public function getProductQuestionList($filter = null, $paginate = false, $limit = 15) {
        $productQuestion = ProductQuestion::join('product', 'product.id', '=', 'product_question.product_id')
            ->join('user', 'user.id', '=', 'product_question.user_id')
            ->select(
                'product_question.id', 'product_question.product_id', 'product_question.user_id', 'product_question.created_at',
                'product_question.answer', 'product_question.question',
                'user.name as user_name', 'user.phone as user_phone', 'user.email as user_email',
                'product.title as product_title', 'product.slug as product_slug'
            )
            ->orderBy('id', 'desc');

        if ($filter != null && isset($filter['product_id']) && $filter['product_id'] != '') {
            $productQuestion->where('product_id', $filter['product_id']);
        }

        if ($filter != null && isset($filter['answered']) && $filter['answered'] != '') {
            $productQuestion->whereNotNull('answer');
        }

        if ($filter != null && isset($filter['user_id']) && $filter['user_id'] != '') {
            $productQuestion->where('user_id', $filter['user_id']);
        }

        if ($filter != null && isset($filter['title']) && $filter['title'] != '') {
            $productQuestion->where('product.title', 'like', '%' . $filter['title'] . '%');
        }

        if ($paginate === true) {
            $productQuestion = $productQuestion->paginate($limit);
        } else {
            $productQuestion = $productQuestion->get();
        }

        return $productQuestion;
    }

    public function storeProductQuestion($request) {
        $productQuestion = new ProductQuestion();

        $productQuestion->question = $request->question;
        $productQuestion->product_id = $request->product_id;
        $productQuestion->user_id = Auth::user()->id;
        $productQuestion->created_by = Auth::user()->id;

        $productQuestion->save();

        return [
            'message' => 'Sua pergunta foi enviada. Responderemos a você pelo email de seu cadastro.',
            'data' => $productQuestion
        ];
    }

    public function deleteProductQuestionByProductId ($productId) {
        $productQuestion = $this->getProductQuestionByProductId($productId);

        if ($productQuestion == null) {
            throw new AppException('Cadastro [' . $productId . '] não encontrado.');
        }

        $productQuestion->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $productQuestion->save();

        return [
            'message' => 'Produto removido da lista de favoritos.'
        ];
    }

    public function updateProductQuestion($request, $id) {
        $productQuestionInstance = new ProductQuestion();
        $productQuestion = $productQuestionInstance->getProductQuestionById($id);

        if ($productQuestion == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productQuestion->answer = $request->answer;
        $productQuestion->answered_by = Auth::user()->id;

        $productQuestion->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $productQuestion
        ];
    }

    public function deleteProductQuestion ($id) {
        $productQuestion = $this->getProductQuestionById($id);

        if ($productQuestion == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $productQuestion->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $productQuestion->save();

        return [
            'message' => 'Produto removido da lista de favoritos.'
        ];
    }

    public function sendMailQuestion($product, $productQuestion) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => env('MAIL_TO_ADDRESS'),
            'to_name' => env('MAIL_TO_NAME')
        ];
        $loggedUser = Auth::user();
        $subject = 'Pergunta sobre produto no site';
        $template = 'product-question';
        $data = $product;
        $data->name = $loggedUser->name;
        $data->email = $loggedUser->email;
        $data->question = $productQuestion->question;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }

    public function sendMailAnswer($product, $productQuestion, $user) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => $user->email,
            'to_name' => $user->name
        ];
        $subject = 'Resposta sobre produto no site';
        $template = 'product-answer';
        $data = $product;
        $data->answer = $productQuestion->answer;
        $data->question = $productQuestion->question;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }
}
