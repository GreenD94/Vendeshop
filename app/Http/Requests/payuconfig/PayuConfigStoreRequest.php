<?php

namespace App\Http\Requests\payuconfig;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PayuConfigStoreRequest extends FormRequest
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
        return  $authUser->hasRole(['admin', 'master']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'is_active' => ['required', 'boolean'],
            'api_key' => ['required'],
            'api_login' => ['required'],
            'merchant_id' => ['required'],
            'is_test' => ['required', 'boolean'],
            'payments_custom_url' => ['required'],
            'reports_custom_url' => ['required'],
            'account_id' => ['required'],
            'description' => ['required'],
            'tax_value' => ['required'],
            'tax_return_base' => ['required'],
            'currency' => ['required'],
            'installments_number' => ['required']
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
