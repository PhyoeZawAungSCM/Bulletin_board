<?php
namespace App\Contracts\Dao\User;

use App\Models\User;
use Illuminate\Http\Request;

interface UserDaoInterface{

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
     * @return User $user
     */
    public function store($validated);

    /**
     * to update a user
     * @param Request $request
     * @param User $user
     * @return User $user
     */
    public function update(Request $request ,User $user);

    /**
     * to delete a user
     */
    public function destroy(User $user);
}