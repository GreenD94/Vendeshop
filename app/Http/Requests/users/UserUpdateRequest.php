<?php

namespace App\Http\Requests\users;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    use Responser;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {




        $uniqueRule = Rule::unique('users');
        if (!!$this->id) return  [
            'id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1'],
            'first_name' =>  [],
            'last_name' =>  [],
            'email' =>  [Rule::unique('users')->ignore($this->id), 'email'],
            'password' =>  ['min:6'],
            'phone' =>  ['numeric'],
           // 'birth_date' =>  ['date_format:Y-m-d H:i:s'],
            'avatar' => ['image']
        ];

        return [
            'id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1'],
            'first_name' =>  [],
            'last_name' =>  [],
            'email' =>  ['email'],
            'password' =>  ['min:6'],
            'phone' =>  ['numeric'],
           // 'birth_date' =>  ['date_format:Y-m-d H:i:s'],
            'avatar' => ['image']
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
