<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;
use Auth;

class ContactController extends Controller
{

    /**
     * Contacts index view
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $contacts = Contact::with('account')
        ->whereHas('account', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->get();

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts
            ]);
    }

    /**
     * Show selected contact details
     *
     * @param  App\Models\Contact  $contact
     * @return \Inertia\Response
     */
    public function show(Contact $contact)
    {
        $contact->load('account');

        return Inertia::render('Contacts/Show', [
            'contact' => $contact,
            ]);
    }

    /**
     * Show the form for creating a new contact
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $user = Auth::user();
        $accounts = Account::where('owner_id', $user->id)->get();

        return Inertia::render('Contacts/Create', [
            'accounts' => $accounts
            ]);
    }

    /**
     * Store new Contact
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contacts',
            'phone' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'account' => 'required|exists:accounts,id',
        ]);
        $contact = new Contact([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
            'account_id' => $request->input('account'),
        ]);
        $contact->save();

        return redirect()->route('contacts.index');
    }

    /**
     * Create new Contact
     *
     * @param  \App\Models\Contact  $contact
     * @return \Inertia\Response
     */
    public function edit(Contact $contact)
    {
        $contact->load('account');
        $accounts = Account::where('owner_id', $contact->account->owner_id)->get();

        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'accounts' => $accounts
            ]);
    }

    /**
     * Update existing contact
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'account' => 'required',
        ]);
        $contact->first_name = $request->input('first_name');
        $contact->last_name = $request->input('last_name');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->position = $request->input('position');
        $contact->account_id = $request->input('account');
        $contact->save();

        return redirect()->route('contacts.index');
    }

    /**
     * Destroy contact
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}
