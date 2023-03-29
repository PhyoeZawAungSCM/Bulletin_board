<?php
namespace App\Contracts\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthServiceInterface{
    /**
     * Login user 
     * @param Array $validated
     * @return json object with message
     */
    public function login($validated);

    /**
     * Logout user
     * @param Request $request
     * @return  Response json object with message
     */
    public function logout(Request $request);

    /**
     * Update user service
     * @param Request $request
     * @param User $user
     * @return Response JSON data with message of success or not and $user
     */
    public function update(Request $request,User $user);

    /**
     * Change password for the use
     * @param $password
     * @return $user
     */
    public function changePassword(Request $request);

     /**
     * Requesting for password reset link 
     * @param Request $request['email']
     * @return Response JSON with message of success or not
     */
    public function forgotPassword(Request $request);

    /**
     * Check for the imcoming token exists or not
     * @param Request $request['token']
     * @return Response JSON with message of success or not 
     */
    public function checkToken(Request $request);

    /**
     * Resetting the password with token
     * @param Request $request['token','password','password_confirmation']
     * @return Response JSON with message of success or not
     */
    public function resetPassword(Request $request);
}
