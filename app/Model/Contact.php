<?php

namespace App\Model;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Helper;

class Contact extends Model {
    protected $table = 'contact';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $validationRules = [
        'name' => 'required',
        'email' => 'required',
        'message' => 'required'
    ];

    protected $validationMessages = [
        'name.required' => 'O campo nome é obrigatório.',
        'email.required' => 'O campo email é obrigatório.',
        'message.required' => 'O campo mensagem é obrigatório.'
    ];

    public function validateRequest ($request) {
        $validator = Validator::make($request->all(), $this->validationRules, $this->validationMessages);

        if ($validator->fails()) {
            return $validator;
        }

        return null;
    }

    public function getContact ($id) {
        return Contact::where('id', $id)
        ->first();
    }

    public function getContactList ($filter = null, $paginate = false, $limit = 15) {
        if ($filter == null) {
            $filter['name'] = '';
        }

        $contact = Contact::orderBy('id', 'desc');

        if (isset($filter['name']) && $filter['name'] != '') {
            $contact->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if ($paginate) {
            $contact = $contact->paginate($limit);
        } else {
            $contact = $contact->take($limit)
                ->get();
        }

        return $contact;
    }

    public function storeContact ($request) {
        $contact = new Contact();

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->created_by = 1;

        $contact->save();

        return ['Cadastro efetuado com sucesso.', $contact];
    }

    public function updateContact ($request, $id) {
        $contact = Contact::getContact($id);

        if ($contact == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;

        $contact->save();

        return ['Cadastro atualizado com sucesso.', $contact];
    }

    public function deleteContact ($id) {
        $contact = Contact::getContact($id);

        if ($contact == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $contact->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $contact->save();

        return 'Cadastro deletado com sucesso.';
    }

    public function sendMail($contact) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => env('MAIL_TO_ADDRESS'),
            'to_name' => env('MAIL_TO_NAME')
        ];
        $subject = 'Nova mensagem no site';
        $template = 'contact';
        $data = $contact;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception('Erro ao enviar email.');
        }
    }
}
