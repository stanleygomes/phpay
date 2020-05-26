<?php

namespace App\Model;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class ContactReply extends Model {
    use SoftDeletes;

    protected $table = 'contact_reply';
    protected $fillable = [
        'message',
        'contact_id',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getContactReplyById ($id) {
        return Contact::where('id', $id)
        ->first();
    }

    public function getContactReplyList ($filter = null, $paginate = false, $limit = 15) {
        $contactReply = ContactReply::join('user', 'user.id', '=', 'contact_reply.created_by')
            ->select(
                'user.id as user_id', 'user.name as user_name',
                'contact_reply.id', 'contact_reply.message', 'contact_reply.created_at', 'contact_reply.message'
            )
            ->orderBy('id', 'desc');

        if ($filter != null && isset($filter['contact_id']) && $filter['contact_id'] != '') {
            $contactReply->where('contact_id', $filter['contact_id']);
        }

        if ($paginate === true) {
            $contactReply = $contactReply->paginate($limit);
        } else {
            $contactReply = $contactReply->get();
        }

        return $contactReply;
    }

    public function storeContactReply ($request) {
        $contactReply = new ContactReply();

        $contactReply->contact_id = $request->contact_id;
        $contactReply->message = $request->message;
        $contactReply->created_by = Auth::user()->id;

        $contactReply->save();

        return [
            'message' => 'Mensagem respondida com sucesso.',
            'data' => $contactReply
        ];
    }

    public function sendMail($contact) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => $contact->email,
            'to_name' => $contact->name
        ];

        $subject = 'Sua resposta chegou';
        $template = 'contact-reply';
        $data = $contact;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }
}
