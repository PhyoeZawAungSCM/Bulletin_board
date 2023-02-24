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
class AuthService implements AuthServiceInterface
{
    private $auth;

    public function __construct(AuthDaoInterface $authDaoInterface)
    {
        $this->auth = $authDaoInterface;
    }

    public function login($validated)
    {
        try {
            User::where('email', $validated['email'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Email does not exist'
            ], 400);
        }
        if ($this->auth->login($validated)) {
            $user = Auth::user();
            if ($user->type == '0') {
                $token =  $user->createToken('Token', ['admin'])->plainTextToken;
            } else {
                $token =  $user->createToken('Token', ['user'])->plainTextToken;
            }

            return response()->json([
                "message" => "Login Success",
                "token" => $token
            ], 200);
        } else {
            return response()->json([
                "message" => "Incorrect Password",
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


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

    public function update(Request $request, User $user)
    {
        try {
            $user = $this->auth->update($request, $user);
            return response()->json([
                'message' => 'user update success',
                'data' => $user
            ],200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'some error occur',
                'error' => $th->getMessage()
            ],500);
        }
    }

    public function changePassword(Request $request)
    {
        return $this->auth->changePassword($request);
    }

    public function forgotPassword(Request $request)
    {
        try{
            // getting the user 
            $user = $this->auth->forgotPassword($request);

            // create a random token for the length of 60
            $token = Str::random(60);

            // store that token in reset_password table 
            $this->auth->createToken($user->email,$token);

            // sent the mail with the link of this token
            Mail::send('resetMail',['token'=>$token,'user'=>$user], function ($message) use($user){
                $message->from('noreply@gmail.com', 'Bulletin_board');
                $message->sender('scm.phyoezawaung@gmail.com', 'Phyoe Zaw Aung');
                $message->to($user->email, $user->name);
                $message->subject('Bulletin_board reset password');
                  
            });

            // if the mail fail
            if(Mail::failures()){
                return response()->json([
                    'message'=>"Some error occur in sending mail please try again"
                ],500);
            }

            // if success 
            return response()->json([
                'message'=>'Email Send with password reset instructions',
            ],200);

            // if no user found in use table because we use findOfFail 
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message'=>'Email does not exist',
                'error'=>'Email does not exist'
            ],422);
        }
    }

    public function checkToken(Request $request)
    {
        try{
            // if the token exists or not
            if($this->auth->checkToken($request)){
                return response()->json([
                    'message'=>'success'
                ],200);
            }
            else{
                return response()->json([
                    'message'=>'token expire'
                ],400);
            }
        // if some error occur
        }catch(Throwable $th){
            return response()->json([
                'error'=>$th->getMessage()
            ],500);
        }
    }

    public function resetPassword(Request $request)
    {
       return $this->auth->resetPassword($request);
    }
}
