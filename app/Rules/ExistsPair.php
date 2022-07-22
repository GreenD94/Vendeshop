<?php

namespace App\Rules;

use App\Traits\Responser;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsPair implements Rule
{

    public $table,$SisterAttribute,$SisterValue;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table,$SisterAttribute,$SisterValue,$newAttribute=null)
    {
        //
        $this->table=$table;
        $this->SisterAttribute=$SisterAttribute;
        $this->SisterValue=  $SisterValue;
        $this->newAttribute=  $newAttribute;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $brotherAttribute=(!!$this->newAttribute)?$this->newAttribute:$attribute;
        $isUniquePair=DB::table($this->table)->where($brotherAttribute,$value)->where($this->SisterAttribute,$this->SisterValue)->exists();
        return $isUniquePair;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The the combination of :attribute and '.$this->SisterAttribute.' does not exists';
        //['user_id'=>['one of the combination of user_id and roles_id does not exist'],'roles_id'=>['one of the combination of roles_id and user_id does not exist']]
    }
}
