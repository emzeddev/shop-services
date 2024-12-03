<?php

namespace Modules\Attribute\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Code;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class AttributeFamilyUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'code'                      => ['required', 'unique:attribute_families,code,' . $id, new Code],
            'name'                      => 'required',
            'attribute_groups.*.code'   => 'required',
            'attribute_groups.*.name'   => 'required',
            'attribute_groups.*.column' => 'required|in:1,2',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * پیام‌های خطای اعتبارسنجی.
     */
    public function messages(): array
    {
        return [
            'code.required' => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.code')]),
            'code.unique' => trans('attribute::validation.unique', ['attribute' => trans('attribute::attributes.code')]),
            'name.required' => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.name')]),
            'attribute_groups.*.code.required' => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.attribute_groups.*.code')]),
            'attribute_groups.*.name.required' => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.attribute_groups.*.name')]),
            'attribute_groups.*.column.required' => trans('attribute::validation.required', ['attribute' => trans('attribute::attributes.attribute_groups.*.column')]),
            'attribute_groups.*.column.in' => trans('attribute::validation.in', ['attribute' => trans('attribute::attributes.attribute_groups.*.column')]),
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
