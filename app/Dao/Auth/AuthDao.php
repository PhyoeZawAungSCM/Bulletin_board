<?php

namespace App\Dao\Auth;

use App\Contracts\Dao\Auth\AuthDaoInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * System Name : Bulletin Board
 * Module Name : Auth Dao
 */
class AuthDao implements AuthDaoInterface
{
	/**
	 * Login the user
	 * @param Array $validated
	 * @return Boolean if login success or not
	 */
	public function login($validated)
	{
		return Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']]);
	}

	/**
	 * logout the user
	 * @param Request $request
	 */
	public function logout(Request $request)
	{
		// logout no need to do here
	}

	/**
	 * update a user profile
	 * @param Request $request
	 * @param User $user
	 * @return User $user
	 */
	public function update(Request $request, User $user)
	{
		// if the request has a image file
		if ($request->hasFile('profile')) {
			// delete the image that already store in storage
			Storage::delete($user->profile);

			// store new file nad get the path of this file
			$path = $request->file('profile')->store('profiles');

			// updating the user profile
			$user->update([
				'name'    => $request->name,
				'email'   => $request->email,
				'type'    => $request->type,
				'phone'   => $request->phone,
				'dob'     => $request->dob,
				'address' => $request->address,
				'profile' => $path,
			]);
			return $user;
		}
		// if the request has not a profile image file
		$user->update([
			'name'    => $request->name,
			'email'   => $request->email,
			'type'    => $request->type,
			'phone'   => $request->phone,
			'dob'     => $request->dob,
			'address' => $request->address,
		]);
		return $user;
	}

	/**
	 * change password of user
	 * @param Request $request
	 * @return Response
	 */
	public function changePassword(Request $request)
	{
		// get the auth user
		$user = Auth::user();

		// check the current password and request password
		if (!Hash::check($request->password, $user->password)) {
			return response()->json([
				'message' => 'password does not match',
				'error'   => 'Current password is wrong'
			], 422);
		}

		// check the current password and new password
		if (Hash::check($request->newPassword, $user->password)) {
			return response()->json([
				'message' => 'New password must be different from old',
				'error'   => 'New password must be different from old'
			], 422);
		}

		// update the user
		$user->update([
			'password' => bcrypt($request->newPassword)
		]);
		return response()->json([
			'message' => 'Password update success'
		], 200);
	}

	/**
	 * Request a password reset link
	 * @param Request $request
	 * @return Boolean
	 */
	public function forgotPassword(Request $request)
	{
		// check the user find or fail and return
		return User::where('email', $request->email)->firstOrFail();
	}

	/**
	 * create token for user and store in datebase
	 * @param String $email
	 * @param String $token
	 * @return void
	 */
	public function createToken($email, $token)
	{
		// create a token in password_reset table
		DB::table('password_resets')->insert([
			'email'      => $email,
			'token'      => $token,
			'created_at' => Carbon::now('Asia/Yangon')
		]);
	}

	/**
	 * check the token
	 * @param Request $request
	 * @return Boolean for the token exist or not
	 */
	public function checkToken(Request $request)
	{
		// check the token exists in table
		return DB::table('password_resets')->where('token', $request->token)->exists();
	}

	/**
	 * Resettin the password with token
	 * @param Request $request
	 * @return Response
	 */
	public function resetPassword(Request $request)
	{
		// begin the database transaction
		DB::beginTransaction();
		try {
			// get the token from password_reset table
			$token = DB::table('password_resets')->where('token', $request->token)->first();

			// get the user from user table
			$user = User::where('email', $token->email)->first();

			// make password reset
			$user->update([
				'password' => bcrypt($request->password)
			]);

			// delete the token after password reset because this is one time change password link
			DB::table('password_resets')->where('token', $request->token)->delete();

			// commit the transcation
			DB::commit();

			return response()->json([
				'message' => 'password update success'
			], 200);
		} catch (Throwable $th) {
			// rollback the the commit
			DB::rollBack();
			return response()->json([
				'message' => 'some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}
}
