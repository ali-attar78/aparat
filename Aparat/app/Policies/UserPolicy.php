<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    use HandlesAuthorization;


    public function follow(User $user, User $otherUser)
    {
        return ($user->id != $otherUser->id) &&
            (!$user->followings()->where('user_id2',$otherUser->id)->count());
    }

    public function unfollow(User $user, User $otherUser)
    {
        return ($user->id != $otherUser->id) &&
            ($user->followings()->where('user_id2',$otherUser->id)->count());
    }

    public function seeFollowingList(User $user)
    {
        return true;
    }



}
