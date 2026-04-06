<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override method login untuk mendukung AJAX
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = auth()->user();

            $redirectPath = $user->role === 'admin' ? '/home' : '/';

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => $redirectPath
                ]);
            }
            return redirect($redirectPath);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Override respons gagal untuk JSON
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => trans('auth.failed'),
            ], 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([$this->username() => trans('auth.failed')]);
    }
}
