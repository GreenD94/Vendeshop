<?php

namespace App\Http\Requests\order;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class OrderUpdateRequest extends FormRequest
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
            'id' =>  ['required', 'exists:orders,id', 'numeric', 'gte:1'],
            'order_status_id' =>  ['exists:order_statuses,id', 'numeric', 'gte:1', 'integer'],


        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
