<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;
use Auth;

class AccountController extends Controller
{

    /**
     * Accounts index view
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $accounts = Account::with('contacts')
                    ->where('owner_id', $user->id)
                    ->get();

        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts
            ]);
    }

    /**
     * Show selected account details
     *
     * @param  App\Models\Account  $account
     * @return \Inertia\Response
     */
    public function show(Account $account)
    {
        $account->load('owner');
        $owner = $account->load('owner');
        $contacts = Contact::where('account_id', $account->id)->get();

        return Inertia::render('Accounts/Show', [
            'account' => $account,
            'owner' => $owner,
            'contacts' => $contacts,
            ]);
    }

    /**
     * Show the form for creating a new account
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $user = Auth::user();
        return Inertia::render('Accounts/Create', [
            'user' => $user
        ]);
    }

    /**
     * Store new Account
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner' => 'required',
            'phone' => 'required|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'town_city' => 'required',
            'post_code' => 'required',
        ]);
        $account = new Account([
            'name' => $request->input('name'),
            'owner_id' => $request->input('owner')['id'],
            'phone' => $request->input('phone'),
            'country' => $request->input('country'),
            'address' => $request->input('address'),
            'town_city' => $request->input('town_city'),
            'post_code' => $request->input('post_code'),
        ]);
        $account->save();

        return redirect()->route('accounts.index');
    }

    /**
     * Edit Account
     *
     * @param  \App\Models\Account  $account
     * @return \Inertia\Response
     */
    public function edit(Account $account)
    {
        $account->load('owner');
        return Inertia::render('Accounts/Edit', [
            'account' => $account,
            'user' => $account->owner
            ]);
    }

    /**
     * Update existing account
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner' => 'required',
            'phone' => 'required|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'town_city' => 'required',
            'post_code' => 'required',
        ]);
        $account->name = $request->input('name');
        $account->phone = $request->input('phone');
        $account->country = $request->input('country');
        $account->address = $request->input('address');
        $account->town_city = $request->input('town_city');
        $account->post_code = $request->input('post_code');
        $account->save();

        return redirect()->route('accounts.index');
    }

    /**
     * Destroy account
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index');
    }
}