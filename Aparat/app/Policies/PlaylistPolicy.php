<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;

class PlaylistPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function addVideo(User $user,Playlist $playlist,Video $video)
    {
        return $user->id === $playlist->user_id && $user->id === $video->user_id;
    }

}
