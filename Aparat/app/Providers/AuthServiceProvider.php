<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use App\Policies\CommentPolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Video::class => VideoPolicy::class,
        User::class => UserPolicy::class,
        Comment::class => CommentPolicy::class,
        Playlist::class => PlaylistPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addMinute(config('auth.token_expiration.token')));
        Passport::refreshTokensExpireIn(now()->addMinute(config('auth.token_expiration.refresh_token')));

        $this->registerGates();
    }

    private function registerGates()
    {
        Gate::before(function ($user , $ability){
            if ($user->isAdmin())
            {
                return true;
            }

        });
    }

}
