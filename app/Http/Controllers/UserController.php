<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->paginate();
        return success_out(UserResource::collection($users), true);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return success_out(UserResource::make($user));
    }

    public function show(User $user)
    {
        return success_out(UserResource::make($user));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->password)
            $data['password'] = bcrypt($data['password']);
        $user->update($data);

        return success_out(UserResource::make($user));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return success_out(UserResource::make($user), false, 'User deleted successfully');
    }
}
