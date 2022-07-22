<?php

namespace App\Http\Requests\order;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class OrderIndexRequest extends FormRequest
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
            'page' =>  ['integer', 'numeric', 'gte:1'],
            'limit' =>  ['integer', 'numeric', 'gte:1'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1'],
            'payment_type_id' =>  ['exists:payment_types,id', 'numeric', 'gte:1', 'integer'],
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
