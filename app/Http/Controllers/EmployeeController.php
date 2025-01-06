<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function getAllEmployees(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');

            $roleIds = Role::whereIn('name', ['Administrator', 'Teacher'])->pluck('id')->toArray();

            $employees = User::query()
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
                })
                ->whereIn('role_id', $roleIds)
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);

            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createEmployee(CreateEmployeeRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $role = Role::findOrFail($validatedData['role_id']);
            $roleName = strtolower(preg_replace('/\s+/', '', $role->name));

            $username = $this->generateUniqueUsername($roleName);

            $user = User::create([
                'role_id' => $validatedData['role_id'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'name' => $validatedData['name'],
                'username' => $username,
            ]);

            return response()->json([
                'message' => 'Employee created successfully.',
                'data' => $user,
            ], 201);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateUniqueUsername($roleName)
    {
        $counter = 1;

        do {
            $username = "{$roleName}{$counter}";
            $exists = User::where('username', $username)->exists();
            $counter++;
        } while ($exists);

        return $username;
    }
}
