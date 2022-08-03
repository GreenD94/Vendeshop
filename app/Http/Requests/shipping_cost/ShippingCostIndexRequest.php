<?php

namespace App\Http\Requests\shipping_cost;

use Illuminate\Foundation\Http\FormRequest;

class ShippingCostIndexRequest extends FormRequest
{
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
            'page' => ['integer', 'numeric', 'gte:1'],
            'limit' => ['integer', 'numeric', 'gte:1'],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
