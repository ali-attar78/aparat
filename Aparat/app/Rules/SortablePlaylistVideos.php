<?php

namespace App\Rules;

use App\Models\Playlist;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SortablePlaylistVideos implements ValidationRule
{

    protected $playlist;

    public function __construct(Playlist $playlist)
    {
        $this->playlist=$playlist;
    }



    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (is_array($value))
        {
            $videos = $this->playlist->videos()->pluck('videos.id')->toArray();
            sort($videos);
            $value=array_map('intval',$value);
            sort($value);
            if ($videos != $value){
                $fail("problem in array sort");
            }
        }
        else{
            $fail("problem in array sort");

        }

    }
}
