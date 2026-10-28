<?php

namespace App\Application\Admin\Controllers\Api\User;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponseTrait;

    private function findUser(string $id): ?User
    {
        return User::where('uuid', $id)->orWhere('id', $id)->first();
    }

    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20);
            $status = $request->input('status');
            $search = $request->input('search');

            $query = $userRepository->filter(compact('status'));

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $users = $query->latest()->paginate((int) $perPage);

            return $this->responseData(data: [
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch users: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to fetch users.'), code: 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->responseError(msg: $validator->errors()->first(), code: 422, data: $validator->errors()->toArray());
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'password' => Hash::make($request->input('password')),
                'status' => 'active',
            ]);

            return $this->responseData(data: [
                'user' => $user,
            ], code: 201, msg: __('User created successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to create user.'), code: 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                return $this->responseError(msg: __('User not found.'), code: 404);
            }
            return $this->responseData(data: [
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to fetch user.'), code: 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                return $this->responseError(msg: __('User not found.'), code: 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'password' => 'nullable|string|min:8',
            ]);

            $data = array_filter($validated, fn($value) => $value !== null && $value !== '');
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return $this->responseData(data: [
                'user' => $user->fresh(),
            ], msg: __('User updated successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to update user.'), code: 500);
        }
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                return $this->responseError(msg: __('User not found.'), code: 404);
            }

            $validated = $request->validate([
                'status' => 'required|string|in:ACTIVE,SUSPENDED,BANNED,active,suspended,banned',
                'reason' => 'nullable|string|max:500',
            ]);

            $user->update([
                'status' => strtolower($validated['status']),
            ]);

            return $this->responseData(data: [
                'user' => $user->fresh(),
            ], msg: __('User status updated successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to update user status: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to update user status.'), code: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                return $this->responseError(msg: __('User not found.'), code: 404);
            }
            $user->delete();
            return $this->responseData(msg: __('User deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return $this->responseError(msg: __('Failed to delete user.'), code: 500);
        }
    }
}
