<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\WishlistItem;

class WishlistItemController extends Controller {
    public function index() {
        try {
            $wishlistItemInstance = new WishlistItem();
            $wishlistItems = $wishlistItemInstance->getWishlistItemList(null, true);

            return view('wishlistItem.index', compact('wishlistItems'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create($productId) {
        try {
            $wishlistItemInstance = new WishlistItem();
            $wishlistItem = $wishlistItemInstance->storeWishlistItem($productId);

            return Redirect::route('website.product.show', ['id' => $productId])
                ->with('status', $wishlistItem['message']);
        } catch (AppException $e) {
            return Redirect::route('app.wishlistItem.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function deleteByProductId($productId) {
        try {
            $wishlistItemInstance = new WishlistItem();
            $delete = $wishlistItemInstance->deleteWishlistItemByProductId($productId);

            return Redirect::route('website.product.show', ['id' => $productId])
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.wishlistItem.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $wishlistItemInstance = new WishlistItem();
            $delete = $wishlistItemInstance->deleteWishlistItem($id);

            return Redirect::route('app.wishlistItem.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.wishlistItem.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
