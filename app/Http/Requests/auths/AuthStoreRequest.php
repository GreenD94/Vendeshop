<?php

namespace App\Http\Requests\auths;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;


class AuthStoreRequest extends FormRequest
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

        if ($this->driver == "google") return [
            'token' =>  ['required'],
            'driver' =>  [Rule::in(['google', 'apple'])],
            'device_key' =>  ['required'],
        ];

        if ($this->driver == "apple") return [
            'token' =>  ['required'],
            'driver' =>  [Rule::in(['google', 'apple'])],
            'device_key' =>  ['required'],
        ];
        return [
            'email' =>  ['required', "exists:users,email", 'email'],
            'password' =>  ['required', "min:6"],
            'driver' =>  [Rule::in(['google'])],
            'device_key' =>  ['required'],
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
