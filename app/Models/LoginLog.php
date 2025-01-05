<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasUuids;

    // Defining fillable attributes
    protected $fillable = [
        'email',
        'tag',
        'ip_address',
        'user_agent',
        'is_successful',
        'x_forwarded_for',
        'logged_in_at',
        'logged_out_at',
        'device',
        'browser',
        'platform',
        'is_mobile',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public static function createLog($request, $action, $isSuccess, $device, $platform, $browser, $isMobile, $uniqTime)
    {
        return self::create([
            'email' => $request->email,
            'tag' => $uniqTime,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent') ?? null,
            'is_successful' => $isSuccess,
            'x_forwarded_for' => $request->header('x_forwarded_for') ?? null,
            'logged_in_at' => $action === 'logged_in' ? now() : null,
            'logged_out_at' => $action === 'logged_out' ? now() : null,
            'device' => $device,
            'browser' => $browser,
            'platform' => $platform,
            'is_mobile' => $isMobile,
        ]);
    }
}
