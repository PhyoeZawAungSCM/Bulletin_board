<?php

namespace App\Http\Controllers\API\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface as AuthAuthServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    private $auth;

    public function __construct(AuthAuthServiceInterface $authServiceInterface)
    {
        $this->auth = $authServiceInterface;
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|min:6|max:20'
        ]);
        return $this->auth->login($validated);
    }
    public function logout(Request $request)
    {
        $this->auth->logout($request);
    }
    public function register(Request $request)
    {
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request, User $user)
    {
        return $this->auth->update($request, $user);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:20',
            'newPassword' => 'required|min:6|max:20|confirmed',
        ]);
        return $this->auth->changePassword($request);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return $this->auth->forgotPassword($request);
    }

    public function checkToken(Request $request)
    {
        $request->validate([
            'token' => 'required|min:60'
        ]);
        return $this->auth->checkToken($request);
    }

    public function resetPassword(Request $request)
    {

        $request->validate([
            'token' => 'required|min:60',
            'password' => 'required|min:6|max:20|confirmed',
        ]);
        return $this->auth->resetPassword($request);
    }
}
