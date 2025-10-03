<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogUserActivity
{
    protected $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $activity = '';
        if ($event instanceof Login) {
            $activity = 'Logged In';
        } elseif ($event instanceof Logout) {
            $activity = 'Logged Out';
        }

        if ($activity && $event->user) {
            ActivityLog::create([
                'user_id' => $event->user->id,
                'activity' => $activity,
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->request->header('User-Agent'),
            ]);
        }
    }
}
