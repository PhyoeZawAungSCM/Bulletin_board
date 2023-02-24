<?php

namespace App\Services\User;

use App\Contracts\Services\User\UserServiceInterface;
use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class UserService implements UserServiceInterface
{
    private $userDao;
    public function __construct(UserDaoInterface $userDaointerface)
    {
        $this->userDao = $userDaointerface;
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
                'data' => $user
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'some error occur',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        return response()->json($this->userDao->show($id));
    }
    public function update(Request $request, User $user)
    {
    }
    public function destroy(User $user)
    {
        try {
            $user = $this->userDao->destroy($user);
            return response()->json([
                'message' => 'user delete success',
                'data' => $user,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'some error occur',
                'error' => $th->getMessage()
            ]);
        }
    }
}
