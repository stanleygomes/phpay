<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Model\Contact;
use App\Helper\Helper;
use Exception;

class ContactController extends Controller {
    public function contact() {
        return view('contact.contact');
    }

    public function send(Request $request) {
        try {
            $contactInstance = new Contact();
            $validateRequest = Helper::validateRequest($request, $contactInstance->validationRules, $contactInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $contactInstance->storeContact($request);
            $contactInstance->sendMail($request);

            return Redirect::back()
                ->with('status', 'Sua mensagem foi enviada com sucesso.');
        } catch(Exception $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        \Session::forget('filtercontact');
        $contacts = Contacts::orderBy('name', 'asc')
            ->paginate(20);
        return view('app.contact.index', compact('contacts'));
    }

    public function show($id) {
        $contact = Contacts::find($id);
        if (!$contact) {
            return redirect()->route('contact.index');
        }
        return view('app.contact.show', compact('contact'));
    }

    public function delete ($id) {
        $contact = Contacts::where('id', $id)
            ->first();
        $contact->delete();
        return redirect()->route('app.contact.index')->with('status', 'Mensagem Excluída.');
    }
}
