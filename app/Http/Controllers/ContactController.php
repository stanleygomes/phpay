<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Model\Contact;
use App\Model\ContactReply;
use App\Helper\Helper;
use App\Exceptions\AppException;

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

            $contact = $contactInstance->storeContact($request);
            $contactInstance->sendMail($request);

            return Redirect::back()
                ->with('status', $contact['message']);
        } catch(AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        try {
            $filter = Session::get('contactSearch');
            $contactInstance = new Contact();
            $contacts = $contactInstance->getContactList($filter, true, 8);

            return view('contact.index', compact('contacts', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function reply($id) {
        try {
            $filter = [
                'contact_id' => $id
            ];

            $contactInstance = new Contact();
            $contact = $contactInstance->getContactById($id);
            $contactReplyInstance = new ContactReply();
            $contactReplies = $contactReplyInstance->getContactReplyList($filter);

            return view('contact.form', compact('contact', 'contactReplies', 'filter'));
        } catch (AppException $e) {
            return Redirect::route('app.contact.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function replyPost(Request $request, $id) {
        try {
            $contactInstance = new Contact();
            $contact = $contactInstance->getContactById($id);
            $contactReplyInstance = new ContactReply();
            $contactReply = $contactReplyInstance->storeContactReply($request);
            $contact->reply = $request->message;
            $contactReplyInstance->sendMail($contact);

            return Redirect::back()
                ->with('status', $contactReply['message']);
        } catch (AppException $e) {
            return Redirect::route('app.contact.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('contactSearch', $filter);
            return Redirect::route('app.contact.index');
        } catch (AppException $e) {
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
        } catch (AppException $e) {
            return Redirect::route('app.contact.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
