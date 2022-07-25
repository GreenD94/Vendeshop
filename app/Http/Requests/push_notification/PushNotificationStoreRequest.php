<?php

namespace App\Http\Requests\push_notification;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;

class PushNotificationStoreRequest extends FormRequest
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

        $rules = [

            'user_id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1', 'integer'],
            'push_notification_event_id' =>  ['required', 'exists:push_notification_events,id', 'numeric', 'gte:1', 'integer'],
            'is_live' =>  ['required', 'boolean'],
            'tittle' =>  ['required'],
            'body' =>  ['required'],

        ];
        if (is_array($this->user_id)) $rules['user_id'] = ['required'];
        if (is_array($this->user_id)) $rules['user_id.*'] = ['exists:users,id', 'numeric', 'gte:1', 'integer'];
        if ($this->user_id == "*") $rules['user_id'] = ['required'];
        return $rules;
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
