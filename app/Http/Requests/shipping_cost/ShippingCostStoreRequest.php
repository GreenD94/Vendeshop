<?php

namespace App\Http\Requests\shipping_cost;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ShippingCostStoreRequest extends FormRequest
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

            'is_active' => ['boolean'],
            'price' => ['required'],
            'price_percentage' => ['required'],
            'poblacion_origen' => ['required'],
            'poblacion_destino' => ['required'],
            'departamento_destino' => ['required'],
            'tipo_envio' => ['required'],
            'd2021_paq' => ['required'],
            'd2021_msj' => ['required'],
            'd1kg_msj' => ['required'],
            'd2kg_msj' => ['required'],
            'd3kj_msj' => ['required'],
            'd4kg_msj' => ['required'],
            'd5kg_msj' => ['required'],

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
