<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginHistory;
use Browser;

class LoginHistoryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $data = [
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'operating_system' => Browser::platformName(),
            'browser' => Browser::browserName(),
            'device_type' => strtolower(Browser::deviceType()),
            'signin' => date("Y-m-d H:i:s")
        ];

        $login_history = new LoginHistory($data);
        $login_history->save();
    }
}
