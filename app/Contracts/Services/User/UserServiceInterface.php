<?php
namespace App\Contracts\Services\User;

use App\Models\User;
use Illuminate\Http\Request;

interface UserServiceInterface{

    /**
     * User of the board
     * @return Users $users of the page based on type 
     */
    public function index();

    /**
     * a spicific user
     * @param User $user
     * @return User $user
     */
    public function show($id);

    /**
     * to store a create user
     * @param Validator $validated
     * @return JSON with $user data with message success or not
     */
    public function store($validated);

    /**
     * to update a user
     * @param Request $request
     * @param User $user
     * @return JSON with $user data with message success or not
     */
    public function update(Request $request,User $user);

    /**
     * to delete a user
     * @param User $user
     * @return JSON with $user data and message success or not
     */
    public function destroy(User $user);
}
