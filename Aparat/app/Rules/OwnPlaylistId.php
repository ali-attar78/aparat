<?php

namespace App\Rules;

use App\Models\Playlist;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnPlaylistId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Playlist::where(['id' => $value])->count()){
        $isOwnPlaylistId = Playlist::where(['id' => $value, 'user_id' => auth()->id()])->count();
            if (!$isOwnPlaylistId) {
                $fail("no access playlist");
            }
        }

        else{
            $fail("invalid playlist id");
        }
    }
}
