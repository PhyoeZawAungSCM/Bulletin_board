<?php

namespace App\Http\Controllers\API\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Services\User\UserServiceInterface;

/**
 *System Name : Bulletin Board
 *Module Name : UserController
 */
class UserController extends Controller
{
	private $userInterface;

	public function __construct(UserServiceInterface $userServiceInterface)
	{
		$this->userInterface = $userServiceInterface;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return $this->userInterface->index();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			'name'     => 'required',
			'email'    => 'required|email',
			'password' => 'required|confirmed|min:6:|max:20',
			'address'  => 'required',
			'profile'  => 'required'
		]);
		return $this->userInterface->store($request);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return $this->userInterface->show($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user)
	{
		return $this->userInterface->update($request, $user);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user)
	{
		return $this->userInterface->destroy($user);
	}
}
