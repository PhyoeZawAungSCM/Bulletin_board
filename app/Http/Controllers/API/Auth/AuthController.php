<?php

namespace App\Http\Controllers\API\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface as AuthAuthServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * System Name:Bulletin Board
 * Module Name:Auth Controller
 */
class AuthController extends Controller
{
	private $auth;

	/**
	 * Constructor for authServiveinterface
	 * @param AuthServiceInterface auth
	 */
	public function __construct(AuthAuthServiceInterface $authServiceInterface)
	{
		$this->auth = $authServiceInterface;
	}

	/**
	 * login as the created user
	 * @param Request $request
	 * @return Illuminate/Http/Response
	 */
	public function login(Request $request)
	{
		$validated = $request->validate([
			'email'    => 'required|email|max:50',
			'password' => 'required|min:6|max:20'
		]);
		return $this->auth->login($validated);
	}

	/**
	 * logout the user if login
	 * @param Request $request
	 * @return Illuminate/Http/Response
	 */
	public function logout(Request $request)
	{
		$this->auth->logout($request);
	}

	/**
	 * Register the user this is not used in this project
	 * because we don't need to registre
	 */
	public function register(Request $request)
	{
	}

	/**
	 * getting the user profile
	 * @param Request $request
	 * @return Illuminate/Http/Response
	 *
	 */
	public function profile(Request $request)
	{
		return response()->json($request->user());
	}

	/**
	 * Update a specific user profile
	 * @return Illuminate/Http/Response
	 */
	public function update(Request $request, User $user)
	{
		return $this->auth->update($request, $user);
	}

	/**
	 * cahnge the current user password
	 * @return  Illuminate/Http/Response
	 */
	public function changePassword(Request $request)
	{
		$request->validate([
			'password'    => 'required|min:6|max:20',
			'newPassword' => 'required|min:6|max:20|confirmed',
		]);
		return $this->auth->changePassword($request);
	}

	/**
	 * forgot password and request email and change password
	 * @return  Illuminate/Http/Response
	 */
	public function forgotPassword(Request $request)
	{
		$request->validate(['email' => 'required|email']);
		return $this->auth->forgotPassword($request);
	}

	/**
	 * Checking the token that come from the link of vue
	 * @return  Illuminate/Http/Response
	 */
	public function checkToken(Request $request)
	{
		$request->validate([
			'token' => 'required|min:60'
		]);
		return $this->auth->checkToken($request);
	}

	/**
	 * Resetting the password through the link
	 * @return Illuminate/Http/Response
	 */
	public function resetPassword(Request $request)
	{
		$request->validate([
			'token'    => 'required|min:60',
			'password' => 'required|min:6|max:20|confirmed',
		]);
		return $this->auth->resetPassword($request);
	}
}
