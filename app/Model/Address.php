<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Exception;

class Address extends Model {
    protected $table = 'address';
    protected $fillable = [
        'user_id',
        'name',
        'zipcode',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'name' => 'required',
        'zipcode' => 'required',
        'street' => 'required',
        'number' => 'required',
        'district' => 'required',
        'city' => 'required',
        'state' => 'required'
    ];

    public $validationMessages = [
        'name.required' => 'O campo nome é obrigatório.',
        'zipcode.required' => 'O campo CEP é obrigatório.',
        'street.required' => 'O campo logradouro é obrigatório.',
        'number.required' => 'O campo número é obrigatório.',
        'district.required' => 'O campo bairro é obrigatório.',
        'city.required' => 'O campo cidade é obrigatório.',
        'state.required' => 'O campo estado é obrigatório.'
    ];

    public function getAddressById($id) {
        return Address::where('id', $id)
            ->first();
    }

    public function getAddressList($filter = null, $paginate = false, $limit = 15) {
        $address = Address::orderBy('id', 'desc');

        if ($filter != null && $filter['user_id'] != '') {
            $address->where('user_id', $filter['user_id']);
        }

        if ($paginate === true) {
            $address = $address->paginate($limit);
        } else {
            $address = $address->get();
        }

        return $address;
    }

    public function storeAddress($request) {
        $address = new Address();

        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->zipcode = $request->zipcode;
        $address->street = $request->street;
        $address->number = $request->number;
        $address->complement = $request->complement;
        $address->district = $request->district;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->created_by = Auth::user()->id;

        $address->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $address
        ];
    }

    public function updateAddress($request, $id) {
        $address = Address::getAddressById($id);

        if ($address == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $address->name = $request->name;
        $address->zipcode = $request->zipcode;
        $address->street = $request->street;
        $address->number = $request->number;
        $address->complement = $request->complement;
        $address->district = $request->district;
        $address->city = $request->city;
        $address->state = $request->state;

        $address->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $address
        ];
    }

    public function deleteAddress ($id) {
        $address = Address::getAddressById($id);

        if ($address == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $address->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $address->save();

        return 'Cadastro deletado com sucesso.';
    }
}
