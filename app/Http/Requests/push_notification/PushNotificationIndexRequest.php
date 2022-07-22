<?php

namespace App\Http\Requests\push_notification;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;

class PushNotificationIndexRequest extends FormRequest
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
            'page' =>  ['numeric', 'gte:1', 'integer'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1', 'integer'],
            'limit' =>  ['numeric', 'gte:1', 'integer'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
