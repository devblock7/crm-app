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

    public function show(Account $account)
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit(Account $account)
    {

    }

    public function update(Request $request, Account $account)
    {

    }

    public function destroy(Account $account)
    {

    }
}