<?php

namespace App\Services\User;

use App\Contracts\Services\User\UserServiceInterface;
use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

/**
 * System Name:Bulletin Board
 * Module Name:UserService
 */
class UserService implements UserServiceInterface
{
	// userDao
	private $userDao;

	/**
	 * @param UserDaoInterface $userDaoInterface
	 */
	public function __construct(UserDaoInterface $userDaoInterface)
	{
		$this->userDao = $userDaoInterface;
	}

	public function index()
	{
		return response()->json($this->userDao->index());
	}

	public function store($request)
	{
		try {
			$user = $this->userDao->store($request);
			return response()->json([
				'message' => 'user create success',
				'data'    => $user
			]);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}

	public function show($id)
	{
		return response()->json($this->userDao->show($id));
	}

	public function update(Request $request, User $user)
	{
		// no need to update the user because that is update in auth after login as this user
	}

	public function destroy(User $user)
	{
		try {
			$user = $this->userDao->destroy($user);
			return response()->json([
				'message' => 'user delete success',
				'data'    => $user,
			]);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}
}
