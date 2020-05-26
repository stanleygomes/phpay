<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Helper\Helper;

class CategoryController extends Controller {
    public function index() {
        try {
            $filter = Session::get('categorySearch');
            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList($filter, true);

            return view('category.index', compact('categories', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('categorySearch', $filter);
            return Redirect::route('app.category.index');
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;

            return view('category.form', compact('modeEdit'));
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $categoryInstance = new Category();
            $validateRequest = Helper::validateRequest($request, $categoryInstance->validationRules, $categoryInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $categoryInstance = new Category();
            $category = $categoryInstance->storeCategory($request);

            return Redirect::route('app.category.index')
                ->with('status', $category['message']);
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $categoryInstance = new Category();
            $category = $categoryInstance->getCategoryById($id);
            $modeEdit = true;

            return view('category.form', compact('category', 'modeEdit'));
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $categoryInstance = new Category();
            $category = $categoryInstance->updateCategory($request, $id);

            return Redirect::route('app.category.index')
                ->with('status', $category['message']);
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $categoryInstance = new Category();
            $message = $categoryInstance->deleteCategory($id);

            return Redirect::route('app.category.index')
                ->with('status', $message);
        } catch (AppException $e) {
            return Redirect::route('app.category.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
