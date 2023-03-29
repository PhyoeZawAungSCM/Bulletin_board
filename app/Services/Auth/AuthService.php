<?php

namespace App\Services\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface;
use Illuminate\Http\Request;
use App\Contracts\Dao\Auth\AuthDaoInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * System Name: Bulletin board
 * Module Name: Auth
 */
class AuthService implements AuthServiceInterface
{
	private $auth;

	/**
	 * @param AuthDoaInterface
	 *
	 */
	public function __construct(AuthDaoInterface $authDaoInterface)
	{
		$this->auth = $authDaoInterface;
	}

	/**
	 * Login  as created user
	 * @param Array $validated
	 * @return Response
	 */
	public function login($validated)
	{
		try {
			// finding the user
			User::where('email', $validated['email'])->firstOrFail();
		} catch (ModelNotFoundException $e) {
			// if the user not found in database
			return response()->json([
				'message' => 'Email does not exist'
			], 400);
		}
		// if the user login attempt complete
		if ($this->auth->login($validated)) {
			// getting the authenticated user
			$user = Auth::user();
			// if the user type is zero that is the admin and create token for admin abality
			if ($user->type == '0') {
				$token = $user->createToken('Token', ['admin'])->plainTextToken;

			// if the user type is zero that is the admin and create token for admin abality
			} else {
				$token = $user->createToken('Token', ['user'])->plainTextToken;
			}

			return response()->json([
				'message' => 'Login Success',
				'token'   => $token
			], 200);
		} else {
			// if user attempt fail
			return response()->json([
				'message' => 'Incorrect Password',
			], JsonResponse::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * Logout the login user
	 * @param Reauest $request
	 * @return Response
	 */
	public function logout(Request $request)
	{
		// invoking the token
		Auth::user()->tokens()->delete();
		Auth::guard('web')->logout();
		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json([
			'message' => 'Logout successfully',
		], 200);
	}

	/**
	 * Update a user pofile
	 * @param Request $request
	 * @param User $user
	 * @return Response
	 */
	public function update(Request $request, User $user)
	{
		try {
			// trying to update the user
			$user = $this->auth->update($request, $user);
			return response()->json([
				'message' => 'user update success',
				'data'    => $user
			], 200);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}

	/**
	 * to change a password
	 * @param Request $request
	 * @return Response
	 */
	public function changePassword(Request $request)
	{
		return $this->auth->changePassword($request);
	}

	/**
	 * Request a password reset link
	 * @param Request $request
	 * @return Response
	 */
	public function forgotPassword(Request $request)
	{
		try {
			// getting the user
			$user = $this->auth->forgotPassword($request);

			// create a random token for the length of 60
			$token = Str::random(60);

			// store that token in reset_password table
			$this->auth->createToken($user->email, $token);

			// sent the mail with the link of with this token
			try {
				Mail::send('resetMail', ['token'=>$token, 'user'=>$user], function ($message) use ($user) {
					$message->from('noreply@gmail.com', 'Bulletin_board');
					$message->sender('scm.phyoezawaung@gmail.com', 'Phyoe Zaw Aung');
					$message->to($user->email, $user->name);
					$message->subject('Bulletin_board reset password');
				});
			} catch(Throwable $th) {
				return response()->json([
					'message'=> 'Some error occur in sending mail please try again later',
					'error'  => $th->getMessage()
				], 500);
			}
						// if the mail fail
						if (Mail::failures()) {
							return response()->json([
								'message'=> 'Some error occur in sending mail please try again'
							], 500);
						}

						// if success
						return response()->json([
							'message'=> 'Email Send with password reset instructions',
						], 200);

			// if no user found in use table because we use findOfFail
		} catch(ModelNotFoundException $e) {
			return response()->json([
				'message'=> 'Email does not exist',
				'error'  => 'Email does not exist'
			], 422);
		}
	}

	/**
	 * checking the token from the link
	 * @param Request $request
	 * @return Response
	 */
	public function checkToken(Request $request)
	{
		try {
			// if the token exists or not
			if ($this->auth->checkToken($request)) {
				return response()->json([
					'message'=> 'success'
				], 200);
			} else {
				return response()->json([
					'message'=> 'token expire'
				], 400);
			}
			// if some error occur
		} catch(Throwable $th) {
			return response()->json([
				'error'=> $th->getMessage()
			], 500);
		}
	}

	/**
	 * Resetting the password
	 * @param Request $request
	 * @return Response
	 */
	public function resetPassword(Request $request)
	{
		return $this->auth->resetPassword($request);
	}
}
