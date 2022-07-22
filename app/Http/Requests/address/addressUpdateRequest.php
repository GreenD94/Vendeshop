<?php

namespace App\Http\Requests\address;


use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class addressUpdateRequest extends FormRequest
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
            'id' =>  ['required', 'exists:addresses,id', 'numeric', 'gte:1'],
            'address' =>  [],
            'city_id' =>  [],
            'street' =>  [],
            'postal_code' =>  [],
            'deparment' =>  [],
            'phone_number' =>  [],
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
