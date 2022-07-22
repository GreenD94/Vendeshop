<?php

namespace App\Http\Requests\order;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class OrderStoreRequest extends FormRequest
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
            'user_id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1', 'integer'],
            'payment_type_id' =>  ['required', 'exists:payment_types,id', 'numeric', 'gte:1', 'integer'],
            'stocks' =>  ['required', 'array'],
            'stocks.*.id' =>  ['required', 'exists:stocks,id', 'numeric', 'gte:1', 'integer'],
            'stocks.*.color_id' =>  ['nullable', 'exists:colors,id', 'numeric', 'gte:1', 'integer'],
            'stocks.*.size_id' =>  ['nullable', 'exists:sizes,id', 'numeric', 'gte:1', 'integer'],
            'stocks.*.amount' =>  ['required', 'numeric', 'gte:1', 'integer'],
            // 'tickets' =>  ['array'],
            // 'tickets.*' =>  ['exists:tickets,id', 'numeric', 'gte:1', 'integer'],
            'address_id' =>  ['exists:addresses,id', 'numeric', 'gte:1', 'integer'],
            'billing_address_id' =>  ['exists:addresses,id', 'numeric', 'gte:1', 'integer'],

        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
