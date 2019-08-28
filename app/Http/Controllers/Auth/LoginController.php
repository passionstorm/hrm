<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{

    /**
     * @param LoginRequest $request
     * @return RedirectResponse|Redirector
     */
    public function postLogin(LoginRequest $request)
    {

        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')], true)) {
            $redirect = $request->session()->get('redirect', null);
            $request->session()->forget('redirect');
            return redirect($redirect ? $redirect : 'index');
        } else {
            return redirect('login')->with('fail', 'Login failed');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return View
     */
    public function login()
    {
        return view('login');
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
