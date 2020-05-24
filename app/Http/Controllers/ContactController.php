<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        try {
            $filter = Session::get('contactSearch');
            $contactInstance = new Contact();
            $contacts = $contactInstance->getContactList($filter, true);

            return view('contact.index', compact('contacts', 'filter'));
        } catch (Exception $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('contactSearch', $filter);
            return Redirect::route('app.contact.index');
        } catch (Exception $e) {
            return Redirect::route('app.contact.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $contactInstance = new Contact();
            $message = $contactInstance->deleteContact($id);

            return Redirect::route('app.contact.index')
                ->with('status', $message);
        } catch (Exception $e) {
            return Redirect::route('app.contact.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
