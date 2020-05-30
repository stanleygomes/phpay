<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\ProductQuestion;
use App\Model\Product;
use App\Model\User;
use App\Helper\Helper;

class ProductQuestionController extends Controller {
    public function index() {
        try {
            $filter = Session::get('productQuestionSearch');
            $loggedUser = Auth::user();

            if ($loggedUser->profile === 'CUSTOMER') {
                $filter['user_id'] = $loggedUser->id;
            }

            $productQuestionInstance = new ProductQuestion();
            $productQuestions = $productQuestionInstance->getProductQuestionList($filter, true);

            return view('productQuestion.index', [
                'productQuestions' => $productQuestions,
                'filter' => $filter,
                'loggedUser' => $loggedUser
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('productQuestionSearch', $filter);
            return Redirect::route('app.productQuestion.index');
        } catch (AppException $e) {
            return Redirect::route('app.productQuestion.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $productQuestionInstance = new ProductQuestion();
            $validateRequest = Helper::validateRequest($request, $productQuestionInstance->validationRules, $productQuestionInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $productInstance = new Product();
            $product = $productInstance->getProductById($request->product_id);

            $productQuestion = $productQuestionInstance->storeProductQuestion($request);
            $productQuestionInstance->sendMailQuestion($product, $productQuestion['data']);

            return Redirect::route('website.product.show', ['id' => $request->product_id])
                ->with('status', $productQuestion['message']);
        } catch (AppException $e) {
            return Redirect::route('website.product.show', ['id' => $request->product_id])
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $productQuestionInstance = new ProductQuestion();
            $productQuestion = $productQuestionInstance->getProductQuestionById($id);
            $userInstance = new User();
            $user = $userInstance->getUserById($productQuestion->user_id);

            return view('productQuestion.form', [
                'productQuestion' => $productQuestion,
                'user' => $user
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.productQuestion.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $productQuestionInstance = new ProductQuestion();
            $productQuestion = $productQuestionInstance->updateProductQuestion($request, $id);
            $productQuestionData = $productQuestion['data'];
            $userInstance = new User();
            $user = $userInstance->getUserById($productQuestionData->user_id);
            $productInstance = new Product();
            $product = $productInstance->getProductById($productQuestionData->product_id);
            $productQuestionInstance->sendMailAnswer($product, $productQuestionData, $user);

            return Redirect::route('app.productQuestion.index')
                ->with('status', $productQuestion['message']);
        } catch (AppException $e) {
            return Redirect::route('app.productQuestion.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $productQuestionInstance = new ProductQuestion();
            $delete = $productQuestionInstance->deleteProductQuestion($id);

            return Redirect::route('app.productQuestion.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.productQuestion.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
