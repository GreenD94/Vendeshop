<?php

namespace App\Http\Requests\address;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class addressStoreRequest extends FormRequest
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
        return [

            'city_name' =>  ['required'],
            'address' =>  ['required'],
            'city_id' =>  ['required'],
            'street' =>  ['required'],
            'postal_code' =>  ['required'],
            'deparment' =>  ['required'],
            'phone_number' =>  ['required'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1'],
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
