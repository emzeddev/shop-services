<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class GetTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function messages(): array
    {
        return [
            'email.required' => trans('user::validation.email_required'),
            'email.email' => trans('user::validation.email_invalid'),
            'password.required' => trans('user::validation.password_required'),
            'password.min' => trans('user::validation.password_min'),
        ];
    }

    /**
     * Stop validation on the first failure.
     */
    protected function stopOnFirstFailure(): bool
    {
        return true;
    }


    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @throws HttpResponseException
    */
    protected function failedValidation(Validator $validator)
    {
        // گرفتن اولین خطای موجود
        $firstError = collect($validator->errors()->messages())->first();

        $response = new JsonResponse([
            'message' => trans('user::validation.datagiven'), // می‌توانید این پیام را ترجمه کنید.
            'error' => $firstError[0], // فقط اولین پیام خطا
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
