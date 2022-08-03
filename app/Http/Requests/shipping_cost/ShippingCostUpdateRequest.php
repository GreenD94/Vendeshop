<?php

namespace App\Http\Requests\shipping_cost;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ShippingCostUpdateRequest extends FormRequest
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
        return $authUser->hasRole(['admin', 'master']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'exists:shipping_costs,id', 'numeric', 'gte:1', 'integer'],
            'is_active' => ['boolean'],
            'price_percentage' => [''],
            'poblacion_origen' => [''],
            'poblacion_destino' => [''],
            'departamento_destino' => [''],
            'tipo_envio' => [''],
            'd2021_paq' => [''],
            'd2021_msj' => [''],
            'd1kg_msj' => [''],
            'd2kg_msj' => [''],
            'd3kj_msj' => [''],
            'd4kg_msj' => [''],
            'd5kg_msj' => [''],

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
