<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryId implements ValidationRule
{
    const PUBLIC_CATEGORIES = 'public';
    const PRIVATE_CATEGORIES = 'private';
    const ALL_CATEGORIES = 'all';

    private  $categoryType;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function __construct($categoryType = self::ALL_CATEGORIES)
    {

        $this->categoryType = $categoryType;

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Category::where('id', $value)->count()) {

            if ($this->categoryType === self::PUBLIC_CATEGORIES) {
                $check = Category::where('id', $value)->whereNull('user_id')->count();
                if (!$check) {
                    $fail("invalid category id");
                }
            }
            elseif ($this->categoryType === self::PRIVATE_CATEGORIES) {
                $check =  Category::where('id', $value)->where('user_id',auth()->id())->count();
                if (!$check) {
                    $fail("invalid category id");
                }
            }
            elseif ($this->categoryType === self::ALL_CATEGORIES) {
                $check = Category::where('id', $value)->count();
                if (!$check) {
                    $fail("invalid category id");
                }
            }

        } else {
            $fail("invalid category id");
        }

    }

}
