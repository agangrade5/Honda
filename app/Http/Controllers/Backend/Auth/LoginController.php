<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->userlevel == 6) {
                return redirect()->route('manage-trucks.index');
            }
            return redirect()->route('manage-events.index');
        }
        return view('backend.auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->input('username'))->first();

        if ($user && $user->userpass === $request->input('passwd')) {
            Auth::login($user);
            
            if ($user->userlevel == 6) {
                return redirect()->route('manage-trucks.index')->with('msg', 'The User has been logged in successfully');
            }
            return redirect()->route('manage-events.index')->with('msg', 'The User has been logged in successfully');
        }

        return redirect()->back()
            ->withInput($request->only('username'))
            ->with('msg', 'Error');
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
