<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueForUser implements ValidationRule
{
    private $tableName;
    private $columnName;
    private $user_id;
    private $userIdField;

    public function __construct(string $tableName , string $columnName=null,string $user_id=null,$userIdField='user_id')
    {

        $this->tableName=$tableName;
        $this->columnName=$columnName;
        $this->user_id=$user_id ?? auth()->id();  //agar null bud user jari tush rikhte she
        $this->userIdField=$userIdField;

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $field = !empty($this->columnName)? $this->columnName : $attribute;
       $count= DB::table($this->tableName)
            ->where($field,$value)
            ->where($this->userIdField,$this->user_id)
            ->count();

            if ($count!=0){
                $fail("this value alreaafy exist for this user");
            }
    }
}
