<?php

namespace App\Listeners;

use App\Events\RestoreUserEvent;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Events\AccessTokenCreated;

class ActiveUnregisterUser
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
     * @throws Exception
     */
    public function handle(AccessTokenCreated $event): void
    {
        $user = User::withTrashed()->find($event->userId);
        if ($user->trashed())
        {
            try {
                DB::beginTransaction();
                $user->restore();
                event(new RestoreUserEvent($user));
                Log::info('active unregister user', ['user_id' => $user->id]);
                DB::commit();
            }
            catch (Exception $exception)
            {
                DB::rollBack();
                Log::error($exception);
                throw $exception;
            }
        }
    }
}
