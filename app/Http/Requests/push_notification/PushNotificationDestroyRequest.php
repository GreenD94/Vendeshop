<?php

namespace App\Http\Requests\push_notification;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;

class PushNotificationDestroyRequest extends FormRequest
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
            'id' =>  ['required', 'exists:push_notifications,id', 'numeric', 'gte:1', 'integer'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
