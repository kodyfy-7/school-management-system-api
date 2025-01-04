<?php

namespace App\Services;

use App\Models\LoginLog;

class AuthService
{
    public function checkLoginStatus($email, $tag)
    {
        return LoginLog::where('email', $email)->where('tag', $tag)->first();
    }

    public function createLoginActivity($request, $action, $isSuccess = false, $deviceInfo, $uniqTime)
    {
        $device = $deviceInfo['device'] ? $deviceInfo['device'] : null;
        $platform = $deviceInfo['platform'] ? $deviceInfo['platform'] : null; 
        $browser = $deviceInfo['browser'] ? $deviceInfo['browser'] : null;
        $isMobile = $deviceInfo['is_mobile'];

        LoginLog::createLog($request, $action, $isSuccess, $device, $platform, $browser, $isMobile, $uniqTime);

        return true;
    }
}
