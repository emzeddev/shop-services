<?php

namespace Modules\Attribute\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Code;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class AttributeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // مقدار true یعنی کاربر مجاز است.
    }

    /**
     * قوانین اعتبارسنجی.
     */
    public function rules(): array
    {
        return [
            'code'          => ['required', 'not_in:type,attribute_family_id', 'unique:attributes,code', new Code],
            'admin_name'    => 'required',
            'type'          => 'required',
            'default_value' => 'integer',
        ];
    }

    /**
     * پیام‌های خطای اعتبارسنجی.
     */
    public function messages(): array
    {
        return [
            'code.required'         => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.code')]),
            'code.not_in'           => trans('attribute::validation.not_in', ['attribute' => trans('attribute::attributes.code')]),
            'code.unique'           => trans('attribute::validation.unique', ['attribute' => trans('attribute::attributes.code')]),
            'admin_name.required'   => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.admin_name')]),
            'type.required'         => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.type')]),
            'default_value.integer' => trans('attribute::validation.integer', ['attribute' => trans('attribute::attributes.default_value')]),
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
            'message' => trans('attribute::validation.datagiven'), // می‌توانید این پیام را ترجمه کنید.
            'error' => $firstError[0], // فقط اولین پیام خطا
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
