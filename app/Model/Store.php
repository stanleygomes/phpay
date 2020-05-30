<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Helper\Helper;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\AppException;

class Store extends Model {
    use SoftDeletes;

    protected $table = 'store';
    protected $fillable = [
        'name',
        'razao_social',
        'cpf_cnpj',
        'logo_url',
        'email',
        'phone',
        'whatsapp',
        'zipcode',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'name' => 'required',
        'razao_social' => 'required',
        'cpf_cnpj' => 'required',
        'logo_url' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'zipcode' => 'required',
        'street' => 'required',
        'number' => 'required',
        'complement' => 'required',
        'district' => 'required',
        'city' => 'required',
        'state' => 'required'
    ];

    public $validationMessages = [
        'name.required' => 'O campo nome é obrigatório.',
        'cpf_cnpj' => 'O campo CPF/CNPJ é obrigatório.',
        'logo_url' => 'O campo logotipo é obrigatório.',
        'email' => 'O campo email é obrigatório.',
        'phone' => 'O campo telefone é obrigatório.',
        'zipcode' => 'O campo CEP é obrigatório.',
        'street' => 'O campo logradouro é obrigatório.',
        'number' => 'O campo número é obrigatório.',
        'complement' => 'O campo complemento é obrigatório.',
        'district' => 'O campo bairro é obrigatório.',
        'city' => 'O campo cidade é obrigatório.',
        'state' => 'O campo estado é obrigatório. '
    ];

    public function getStoreById($id) {
        return Store::where('id', $id)
            ->first();
    }

    public function getStoreList($filter = null, $paginate = false, $limit = 15) {
        $store = Store::orderBy('id', 'desc');

        if ($filter != null && isset($filter['name']) && $filter['name'] != '') {
            $store->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if ($paginate === true) {
            $store = $store->paginate($limit);
        } else {
            $store = $store->get();
        }

        return $store;
    }

    public function storeStore($request) {
        $store = new Store();

        $store->name = $request->name;
        $store->razao_social = $request->razao_social;
        $store->cpf_cnpj = $request->cpf_cnpj;
        $store->email = $request->email;
        $store->phone = $request->phone;
        $store->whatsapp = $request->whatsapp;
        $store->zipcode = $request->zipcode;
        $store->street = $request->street;
        $store->number = $request->number;
        $store->complement = $request->complement;
        $store->district = $request->district;
        $store->city = $request->city;
        $store->state = $request->state;
        $store->facebook_url = $request->facebook_url;
        $store->instagram_url = $request->instagram_url;
        $store->youtube_url = $request->youtube_url;
        $store->twitter_url = $request->twitter_url;
        $store->created_by = Auth::user()->id;

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $fileManagerInstance = new Helper();
            $store->logo_url = $fileManagerInstance->uploadFile($file, 'store/logo');
        }

        $store->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $store
        ];
    }

    public function updateStore($request, $id) {
        $store = $this->getStoreById($id);

        if ($store == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $store->name = $request->name;
        $store->razao_social = $request->razao_social;
        $store->cpf_cnpj = $request->cpf_cnpj;
        $store->email = $request->email;
        $store->phone = $request->phone;
        $store->whatsapp = $request->whatsapp;
        $store->zipcode = $request->zipcode;
        $store->street = $request->street;
        $store->number = $request->number;
        $store->complement = $request->complement;
        $store->district = $request->district;
        $store->city = $request->city;
        $store->state = $request->state;
        $store->facebook_url = $request->facebook_url;
        $store->instagram_url = $request->instagram_url;
        $store->youtube_url = $request->youtube_url;
        $store->twitter_url = $request->twitter_url;

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $fileManagerInstance = new Helper();
            $store->logo_url = $fileManagerInstance->uploadFile($file, 'store/logo');
        }

        $store->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $store
        ];
    }

    public function deleteStore ($id) {
        $store = $this->getStoreById($id);

        if ($store == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $store->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $store->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
