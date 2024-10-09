<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = $this->userService->createUser($validated);
        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($this->userService->getUserById($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user = $this->userService->updateUser($validatedData, $user);

        return new UserResource($user);
    }


    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->noContent();
    }
}