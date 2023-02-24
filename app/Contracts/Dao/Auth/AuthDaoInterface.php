<?php
namespace App\Contracts\Dao\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthDaoInterface{
    /**
     * Login user 
     * @param Array $validated
     * @return User user and token
     */
    public function login($validated);

    /**
     * Logout user
     * 
     */
    public function logout(Request $request);

    /**
     * Update profile of login user
     * @param Request $request
     * @param User $user
     * @return User $user
     */
    public function update(Request $request,User $user);

    /**
     * Change password for user
     * @param Request $request
     * @return JSON with message of success or not 
     */
    public function changePassword(Request $request);

    /**
     * Requesting for password reset link 
     * @param Request $request['email']
     * @return User $user find with email
     */
    public function forgotPassword(Request $request);

    /**
     * Create token for user and store in database
     * @param $email for email field
     * @param  $token for token field
     * @return null
     */
    public function createToken($email , $token);

    /**
     * Check for the imcoming token exists or not
     * @param Request $request['token']
     * @return Boolean token exists or not
     */
    public function checkToken(Request $request);

    /**
     * Resetting the password with token
     * @param Request $request['token','password','password_confirmation']
     * @return Array message
     */
    public function resetPassword(Request $request);
}