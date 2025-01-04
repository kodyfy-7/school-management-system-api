<?php

namespace App\Services;

use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class HelperService
{
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    public function generateOtp($chars = 6)
    {
        return Str::password($chars, false, true, false, false);
    }

    public function getLocation($ip)
    {
        // return Location::get($ip);
        return null;
    }

    public function getDeviceInfo()
    {
        $agent = new Agent();

        // Get the user's device
        $device = $agent->device();
        // Get the user's browser
        $browser = $agent->browser();

        // Get the user's platform
        $platform = $agent->platform();

        // Check if the device is a mobile
        $isMobile = $agent->isMobile();

        // Example response
        return [
            'device' => $device,
            'browser' => $browser,
            'platform' => $platform,
            'is_mobile' => $isMobile,
        ];
    }

    public function getDevice()
    {
        return $this->agent->device();
    }

    public function getBrowser()
    {
        return $this->agent->browser();
    }

    public function getPlatform()
    {
        return $this->agent->platform();
    }

    public function generateUniqueSlug($baseSlug, $modelType)
    {
        do {
            $slug = Str::random(4);
            $temp_slug = $baseSlug.'-'.$slug;
        } while ($modelType::where('slug', $temp_slug)->exists());

        return $temp_slug;
    }

    public function getAdminBaseUrl()
    {
        $isLocalEnv = env('APP_ENV') === 'local';
        $prodFrontendAdminUrl = env('PROD_FRONTEND_ADMIN_URL');
        $testFrontendAdminUrl = env('TEST_FRONTEND_ADMIN_URL');

        if ($isLocalEnv) {
            $base_url = $testFrontendAdminUrl;
        } else {
            $base_url = $prodFrontendAdminUrl;
        }

        return $base_url;
    }
}
