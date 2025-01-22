<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class MassUpdateRequest extends FormRequest
{
    /**
     * Determine if the request is authorized or not.
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
            'indices'      => ['required', 'array'],
            'indices.*'    => ['integer'],
            'value'        => ['required'],
        ];
    }


    /**
     * Get custom validation messages for the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'indices.required'   => __('category::validation.indices_required'),
            'indices.array'      => __('category::validation.indices_array'),
            'indices.*.integer'  => __('category::validation.each_index_integer'),
            'value.required'     => __('category::validation.value_required'),
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
            'message' => trans('category::validation.datagiven'), // می‌توانید این پیام را ترجمه کنید.
            'error' => $firstError[0], // فقط اولین پیام خطا
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}