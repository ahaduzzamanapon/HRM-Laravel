<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Define custom login field name.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     * Employees log in using Employee ID (emp_id).
     * All other roles (Admin, HR, etc.) log in using their Email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $loginInput = trim($request->input('login', $request->input('emp_id', $request->input('email', ''))));

        if (empty($loginInput)) {
            return [
                'email'    => null,
                'password' => $request->input('password'),
            ];
        }

        // Search for user by email or emp_id
        $user = User::where('email', $loginInput)
            ->orWhere('emp_id', $loginInput)
            ->first();

        if ($user) {
            $isEmployee = false;
            if ($user->role) {
                $roleName = strtolower($user->role->name ?? '');
                $roleKey  = strtolower($user->role->key ?? '');
                if ($roleName === 'employee' || $roleKey === 'employee') {
                    $isEmployee = true;
                }
            }

            // Employee accounts must log in with Employee ID (emp_id)
            if ($isEmployee && $loginInput === $user->email && $loginInput !== $user->emp_id) {
                throw ValidationException::withMessages([
                    $this->username() => ['Employees must log in using their Employee ID.'],
                ]);
            }

            return [
                'email'    => $user->email,
                'password' => $request->input('password'),
            ];
        }

        // Return impossible credentials so auth fails gracefully with standard invalid error
        return [
            'email'    => null,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
