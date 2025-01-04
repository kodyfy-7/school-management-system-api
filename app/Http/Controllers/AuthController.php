<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use App\Services\HelperService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $helperService;

    private $mailService;

    private $authService;

    public function __construct(
        HelperService $helperService,
        // MailService $mailService,
        AuthService $authService, )
    {
        $this->helperService = $helperService;
        // $this->mailService = $mailService;
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                //use relationship
                $role = Role::where('id', $user->role_id)->select('name')->first();

                $expiration = now()->addHours(10);
                $uniqTime = microtime(true);
                $token = $user->createToken($uniqTime, [$role->name], $expiration)->plainTextToken;

                // $token = $user->createToken('Personal Access Token', [$role->name])->accessToken;

                $deviceInfo = $this->helperService->getDeviceInfo();
                $isSuccess = true;
                $action = 'logged_in';
                $this->authService->createLoginActivity($request, $action, $isSuccess, $deviceInfo, $uniqTime);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'user' => [
                            'user_id' => $user->id,
                            'name' => $user->name,
                        ],
                        'role' => [
                            'name' => $role->name,
                        ],
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ],
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user && $user->currentAccessToken()) {
                $tokenData = $user->currentAccessToken();
                $email = $tokenData->tokenable->email;
                $name = $tokenData->name;

                $loginActivity = $this->authService->checkLoginStatus($email, $name);
                if ($loginActivity) {
                    $loginActivity->update([
                        'logged_out_at' => now(),
                    ]);
                    $tokenData->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'You have logged out successfully',
            ], 200);

        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function testDevice()
    {
        $deviceInfo = $this->helperService->getDeviceInfo();
        $isSuccess = true;
        $action = 'logged_in';

        return $deviceInfo;
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'token' => $token,
            ],
            'token_type' => 'Bearer',
        ], 200);
    }
}
