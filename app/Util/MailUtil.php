<?php

namespace App\Helper;

use Illuminate\Support\Facades\Mail;

class MailUtil {
    public function send($param, $data, $template, $subject) {
        Mail::send('Mail/' . $template, ['data' => $data], function ($m) use ($param, $subject) {
            $m->from($param['from_email'], $param['from_name']);
            $m->to($param['to_email'], $param['to_name'])->subject($subject);
        });
    }
}
