<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginHistory;

class LogoutHistoryListener
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
    public function handle(Logout $event): void
    {
        $login_history = LoginHistory::where('user_id', $event->user->id)->whereNull('signout')->orderBy('signin', 'desc')->first();
        if ($login_history) {
            $login_history->signout = date("Y-m-d H:i:s");
            $login_history->save();
        }
    }
}
