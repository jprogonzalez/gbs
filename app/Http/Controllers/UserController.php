<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function index()
    {
        try {
            $usersQuery = User::query();

            $usersQuery->select([
                'id',
                'uuid',
                'name',
                'last_name',
                'role_id',
                'status',
                'email',
                'created_at'
            ]);

            $usersQuery->with('role:id,uuid,name,key');

            $usersQuery->searchPattern(request()->pattern, [
                'name',
                'last_name',
                'email'
            ]);

            $usersQuery->role(request()->role);

            $usersQuery->orderBy('created_at', 'desc');

            $users = $usersQuery->paginate(request()->per_page);

            return $this->responseWithResource(UserResource::collection($users), 'users.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.index');
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $user = User::create($validatedData);

            $user->load(['role:id,uuid,name,key']);

            return $this->responseWithResource(new UserResource($user), 'users.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.store');
        }
    }

    public function show($uuid)
    {
        try {
            $user = User::query()
                ->select([
                    'id',
                    'uuid',
                    'name',
                    'last_name',
                    'role_id',
                    'status',
                    'email',
                    'metadata',
                    'created_at'
                ])
                ->with('role:id,uuid,name,key')
                ->where('uuid', $uuid)
                ->firstOrFail();

            return $this->responseWithResource(new UserResource($user), 'users.show');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.show');
        }
    }

    public function update(UserUpdateRequest $request, $uuid)
    {
        try {
            $user = User::query()
                ->where('uuid', $uuid)
                ->firstOrFail();

            $user->update($request->validated());

            $user->load(['role:id,uuid,name,key']);

            return $this->responseWithResource(new UserResource($user), 'users.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.update');
        }
    }

    public function updateStatus(Request $request, $userUuid)
    {
        try {
            $user = User::query()
                ->select([
                    'id',
                    'uuid',
                    'name',
                    'last_name',
                    'role_id',
                    'status',
                    'email',
                    'created_at'
                ])
                ->where('uuid', $userUuid)
                ->firstOrFail();

            $user->status = !$user->status;
            $user->save();

            $user->load(['role:id,uuid,name,key']);

            return $this->responseWithResource(new UserResource($user), 'users.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.update');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::query()
                ->where('uuid', $id)
                ->firstOrFail();

            $user->delete();

            return $this->responseWithMessage('users.destroy');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.destroy');
        }
    }
}
