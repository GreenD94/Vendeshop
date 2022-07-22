<?php

namespace App\Http\Requests\email;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class EmailStoreRequest extends FormRequest
{
    use Responser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authUser = User::find(Auth()->id());
        return  $authUser->hasRole(['admin', 'master']);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'to' =>  ['required', 'array'],
            'mainTitle' =>  ['required'],
            'secundaryTitle' =>  ['required'],
            'body' =>  ['required'],
            'logoHref' =>  [''],
            'logoSrc' =>  [''],
            'facebookHref' =>  [''],
            'facebookSrc' =>  [''],
            'instagramHref' =>  [''],
            'instagramSrc' =>  [''],
            'youtubekHref' =>  [''],
            'youtubekSrc' =>  [''],
            'buttonHref' =>  [''],
            'buttonText' =>  [''],

        ];
        if ($this->to == '*') $rules['to'] = [];
        return $rules;
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
