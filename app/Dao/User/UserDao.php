<?php

namespace App\Dao\User;

use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * System Name:BulletinBoard
 * Module Name:UserDao
 */
class UserDao implements UserDaoInterface
{
	/**
	 * Users of the bulletin board
	 * @return User $users based on the type of user
	 */
	public function index()
	{
		// getting the user
		$user = Auth::user();
		// if the user is admin return all the users
		if ($user->type == '0') {
			return User::when(
				// if the request has something to search
				(request('name') || request('email') || request('startDate') != null || request('endDate') != null),
				function ($query) {
					$query->where('users.name', 'like', '%' . request('name') . '%')
						->where('users.email', 'like', '%' . request('email') . '%')
						->when(
							(request('endDate') != null),
							function ($query) {
								$query->whereBetween(
									'users.created_at',
									[request('startDate') ? request('startDate') : '', request('endDate') ? request('endDate') : '']
								);
							}
						);
				}
			)->orderBy('id', 'desc')->paginate(config('constant.paginatePage'));
		}
		// if the user is not admin
		return User::where('create_user_id', $user->id)
			->when(
				(request('name') || request('email') || request('startDate') != null || request('endDate') != null),
				function ($query) {
					$query->where('users.name', 'like', '%' . request('name') . '%')
						->where('users.email', 'like', '%' . request('email') . '%')
						->when(
							(request('endDate') != null),
							function ($query) {
								$query->whereBetween(
									'users.created_at',
									[request('startDate') ? request('startDate') : '', request('endDate') ? request('endDate') : '']
								);
							}
						);
				}
			)->orderBy('id', 'desc')->paginate(config('constant.paginatePage'));
	}

	/**
	 * to store a created user
	 * @param Request $request
	 * @return User $user
	 */
	public function store($request)
	{
		$path = $request->file('profile')->store('profiles');
		Log::info(asset('storage/' . $path));
		$user = User::create([
			'name'            => $request->name,
			'email'           => $request->email,
			'password'        => bcrypt($request->password),
			'type'            => $request->type,
			'phone'           => $request->phone,
			'dob'             => $request->dob,
			'address'         => $request->address,
			'profile'         => $path,
			'create_user_id'  => $request->create_user_id,
			'updated_user_id' => $request->updated_user_id,
		]);
		return $user;
	}

	/**
	 * to show a specific user
	 * @param User $id of the user
	 * @return User $user
	 */
	public function show($id)
	{
		// $User = DB::select(DB::raw('select
		//                 user.id,
		//                 user.name,
		//                 user.email,
		//                 user.profile,
		//                 user.type,
		//                 user.phone,
		//                 user.address,
		//                 user.dob,user.
		//                 created_at,user.
		//                 updated_at,
		//                 creator.name as creatorName ,
		//                 updator.name as updatorName
		//                 from users as user
		//                 inner join users as creator on user.create_user_id = creator.id
		//                 inner join users as updator on user.updated_user_id = updator.id
		//                 where user.id = ?'), [$id]);

		// return $User;
		// $user = Db::table('users as user')
		// 	->join('users as creator', 'user.create_user_id', '=', 'creator.id')
		// 	->join('users as updator', 'user.updated_user_id', '=', 'updator.id')
		// 	->where('user.id', $id)
		// 		->select('user.*', 'updator.name as updator_name', 'creator.name as creator_name')
		// 		->get();
		// $user = User::where('id',$id)->get();
		$user = User::where('users.id', $id)
		->join('users as creator', 'users.create_user_id', '=', 'creator.id')
		->join('users as updator', 'users.updated_user_id', '=', 'updator.id')
		->select('users.*', 'creator.name as creatorName', 'updator.name as updatorName')
		->get();
		return $user;
	}

	public function update(Request $request, User $user)
	{
	}

	/**
	 * to delete a user
	 * @param User $user
	 * @return User $user
	 */
	public function destroy(User $user)
	{
		$user->delete();
		return $user;
	}
}
