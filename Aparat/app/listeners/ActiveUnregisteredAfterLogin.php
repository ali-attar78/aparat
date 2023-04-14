<?php

namespace App\listeners;

use App\Events\ActiveUnregisteredUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Lcobucci\JWT\Exception;

class ActiveUnregisteredAfterLogin
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
    public function handle(AccessTokenCreated $event): void
    {
        $user = User::withTrashed()->find($event->userId);
        if ($user->trashed()){
            try {
                DB::beginTransaction();
                $user->restore();
                event(new ActiveUnregisteredUser($user));
                Log::info("active unregister user", ['user_id'=>$user->id]);
                DB::commit();
            }
            catch (Exception $exception){
                DB::rollBack();
                Log::error($exception);
                throw $exception;
            }
        }
    }

}
