<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'title',
        'price',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getProduct($id) {
        return Product::where('id', $id)
            ->first();
    }

    public function getAllProduct() {
        return Product::get();
    }
}
