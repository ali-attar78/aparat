<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoFavourite;
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
        return $video && $video->isAccepted() &&
            (
                $video->user_id != $user->id &&
                VideoRepublish::where([
                    'user_id' => $user->id,
                    'video_id' => $video->id
                ])->count() < 1

            );
    }

    public function like(User $user=null,Video $video=null)
    {
        if ( $video && $video->isAccepted()){

            $conditions =[
                'video_id' => $video->id,
                'user_id' => $user ? $user->id : null,
            ];

            if (empty($user))
            {
                $conditions['user_ip'] = client_ip();
            }

            return  VideoFavourite::where($conditions)->count()==0;

        }

        return false;

    }

    public function unlike(User $user=null,Video $video=null)
    {
            $conditions =[
                'video_id' => $video->id,
                'user_id' => $user ? $user->id : null,
            ];

            if (empty($user))
            {
                $conditions['user_ip'] = client_ip();
            }

            return  VideoFavourite::where($conditions)->count();


        }

    public function seeLikedList(User $user,Video $video=null)
    {
        return true;
    }

    public function delete(User $user,Video $video)
    {
        return $user->id === $video->user_id;
    }

    public function ShowStatistics(User $user,Video $video)
    {
        return $user->id === $video->user_id;
    }

    public function update(User $user,Video $video)
    {
        return $user->id === $video->user_id;
    }


}
