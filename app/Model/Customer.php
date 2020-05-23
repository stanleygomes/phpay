<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $table = 'customer';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'zipcode',
        'street',
        'number',
        'complement',
        'ditrict',
        'city',
        'state',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getCustomer($id) {
        return Customer::where('id', $id)
            ->first();
    }

    public function getAllCustomer() {
        return Customer::get();
    }
}
