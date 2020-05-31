<?php

namespace App\Http\Controllers;

use App\Model\Featured;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Model\Product;
use App\Model\ProductPhoto;
use App\Model\ProductQuestion;
use App\Model\Category;
use App\Model\WishlistItem;

class ProductController extends Controller {
    public function home() {
        try {
            $filterFeatured = [
                'order_position' => true
            ];

            $featuredInstance = new Featured();
            $featureds = $featuredInstance->getFeaturedList($filterFeatured, false);

            $filterProduct = [
                'order_by' => 'id#desc',
                'featured' => 'YES',
                'category_exists' => true,
                'limit' => 6
            ];
            $productInstance = new Product();
            $products = $productInstance->getProductList($filterProduct, false);

            return view('product.home', [
                'featureds' => $featureds,
                'products' => $products
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.contact')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function webSearch(Request $request, $categoryId = 0) {
        try {
            $filter = $request->all();

            if ($filter == null) {
                $filter = [];
            }

            if ($categoryId > 0) {
                $filter['category_id'] = $categoryId;
            }

            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList(null, false);

            $productInstance = new Product();
            $products = $productInstance->getProductList($filter, true, 15);

            return view('product.result', [
                'categories' => $categories,
                'filter' => $filter,
                'products' => $products
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.product.home')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function show($id, $slug = null) {
        try {
            $filterCategory = [
                'product_id' => $id
            ];

            $productInstance = new Product();
            $product = $productInstance->getProductById($id);
            $productPhotoInstance = new ProductPhoto();
            $productPhotos = $productPhotoInstance->getProductPhotoList($filterCategory, false);
            $categoryInstance = new Category();
            $category = $categoryInstance->getCategoryById($product->category_id);

            $filterProduct = [
                'category_id' => $product->category_id,
                'limit' => 4
            ];

            $relatedProducts = $productInstance->getProductList($filterProduct, false);

            $wishlistItemInstance = new WishlistItem();
            $wishlistItem = null;

            if (Auth::user()) {
                $wishlistItem = $wishlistItemInstance->getWishlistItemByProductId($id);
            }

            $filterProductQuestion = [
                'product_id' => $id,
                'answered' => true
            ];

            $productQuestionInstance = new ProductQuestion();
            $productQuestions = $productQuestionInstance->getProductQuestionList($filterProductQuestion, false);

            return view('product.show', [
                'product' => $product,
                'category' => $category,
                'productPhotos' => $productPhotos,
                'relatedProducts' => $relatedProducts,
                'wishlistItem' => $wishlistItem,
                'productQuestions' => $productQuestions
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.product.home')
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return view('product.show');
    }

    public function index() {
        try {
            $filter = Session::get('productSearch');
            $productInstance = new Product();
            $products = $productInstance->getProductList($filter, true);

            return view('product.index', [
                'products' => $products,
                'filter' => $filter
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
            Session::put('productSearch', $filter);
            return Redirect::route('app.product.index');
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $modeEdit = false;
            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList(null, false);
            $productPhotos = [];

            return view('product.form', [
                'modeEdit' => $modeEdit,
                'categories' => $categories,
                'productPhotos' => $productPhotos
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $productInstance = new Product();
            $validateRequest = Helper::validateRequest($request, $productInstance->validationRules, $productInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $product = $productInstance->storeProduct($request);

            return Redirect::route('app.product.index')
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $productInstance = new Product();
            $product = $productInstance->getProductById($id);
            $productPhotoInstance = new ProductPhoto();
            $filter = [
                'product_id' => $id
            ];
            $productPhotos = $productPhotoInstance->getProductPhotoList($filter, false);
            $categoryInstance = new Category();
            $categories = $categoryInstance->getCategoryList(null, false);
            $modeEdit = true;

            return view('product.form', [
                'product' => $product,
                'modeEdit' => $modeEdit,
                'categories' => $categories,
                'productPhotos' => $productPhotos
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $productInstance = new Product();
            $product = $productInstance->updateProduct($request, $id);

            return Redirect::back()
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function photoMain($productId, $photoId) {
        try {
            $productInstance = new Product();
            $product = $productInstance->setPhotoMain($productId, $photoId);

            return Redirect::back()
                ->with('status', $product['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function photoRemove($photoId) {
        try {
            $productPhotoInstance = new ProductPhoto();
            $productPhoto = $productPhotoInstance->deleteProductPhoto($photoId);

            return Redirect::back()
                ->with('status', $productPhoto['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $productInstance = new Product();
            $delete = $productInstance->deleteProduct($id);

            return Redirect::route('app.product.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.product.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
