<?php

namespace App\Providers;

use App\Events\ActiveUnregisteredUser;
use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UploadNewVideo::class=>[
             'App\listeners\ProcessUploadedVideo'
        ],
        VisitVideo::class=>[
            'App\listeners\AddVisitedVideoLogToVideoViewsTable'
        ],
        AccessTokenCreated::class => [
            'App\listeners\ActiveUnregisteredAfterLogin'
        ],
        ActiveUnregisteredUser::class => [

        ],
        DeleteVideo::class => [
            'App\listeners\DeleteVideoData'
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
