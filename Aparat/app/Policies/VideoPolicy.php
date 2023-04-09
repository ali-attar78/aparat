<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{

    use HandlesAuthorization;

    public function changeState(User $user,Video $video=null)
    {

        return $user->isAdmin() ;

    }

    public function republish(User $user,Video $video=null)
    {
        return $video &&
            (
                $video->user_id != $user->id &&
                VideoRepublish::where([
                    'user_id' => $user->id,
                    'video_id' => $video->id
                ])->count() < 1

            );
    }


}
