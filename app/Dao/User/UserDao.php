<?php

namespace App\Dao\User;

use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDao implements UserDaoInterface
{

	public function index()
	{
		$user = Auth::user();
		if ($user->type == '0') {
			return User::when((request('name') || request('email') || request('startDate') != null || request('endDate') != null),
				function ($query) {
					$query->where('users.name', 'like', '%' . request('name') . '%')
						->where('users.email', 'like', '%' . request('email') . '%')
						->when((request('endDate') != null),
							function ($query) {
								$query->whereBetween('users.created_at', [request('startDate') ? request('startDate') : '', request('endDate') ? request('endDate') : '']);
							}
						);
				}
			)->orderBy('id', 'desc')->paginate(5);
		}
		return User::where('create_user_id', $user->id)
			->when((request('name') || request('email') || request('startDate') != null || request('endDate') != null),
				function ($query) {
					$query->where('users.name', 'like', '%' . request('name') . '%')
						->where('users.email', 'like', '%' . request('email') . '%')
						->when((request('endDate') != null),
							function ($query) {
								$query->whereBetween('users.created_at', [request('startDate') ? request('startDate') : '', request('endDate') ? request('endDate') : '']);
							}
						);
				}
			)->orderBy('id', 'desc')->paginate(5);
	}
	public function store($request)
	{
		$path = $request->file('profile')->store('profiles');
		Log::info(asset('storage/' . $path));
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'type' => $request->type,
			'phone' => $request->phone,
			'dob' => $request->dob,
			'address' => $request->address,
			'profile' => $path,
			'create_user_id' => $request->create_user_id,
			'updated_user_id' => $request->updated_user_id,
		]);
		return $user;
	}
	public function show($id)
	{

		$User = DB::select(DB::raw('select 
                        user.id,
                        user.name,
                        user.email,
                        user.profile,
                        user.type,
                        user.phone,
                        user.address,
                        user.dob,user.
                        created_at,user.
                        updated_at,
                        creator.name as creatorName ,
                        updator.name as updatorName
                        from users as user 
                        inner join users as creator on user.create_user_id = creator.id
                        inner join users as updator on user.updated_user_id = updator.id
                        where user.id = ?'), [$id]);

		return $User;
		// $user = DB::table('users')
		// ->join('users','users.create_user_id','=','users.id')
		// ->join('users','users.updated_user_id','=','users.id')
		// ->get();
	}
	public function update(Request $request, User $user)
	{
	}
	public function destroy(User $user)
	{
		$user->delete();
		return $user;
	}
}
