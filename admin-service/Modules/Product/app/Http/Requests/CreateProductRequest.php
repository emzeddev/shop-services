<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Slug;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class CreateProductRequest extends FormRequest
{
   /**
     * تعیین مجوز برای درخواست
     */
    public function authorize(): bool
    {
        return true; // اگر احراز هویت لازم بود، می‌توان این مقدار را تغییر داد
    }

    /**
     * قوانین اعتبارسنجی برای درخواست
     */
    public function rules(): array
    {
        return [
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
            'super_attributes'    => 'array|min:1',
            'super_attributes.*'  => 'array|min:1',
        ];
    }

    /**
     * پیام‌های خطا برای اعتبارسنجی
     */
    public function messages(): array
    {
        return [
            'type.required'                => 'نوع محصول الزامی است.',
            'attribute_family_id.required' => 'انتخاب خانواده ویژگی الزامی است.',
            'sku.required'                 => 'فیلد SKU نباید خالی باشد.',
            'sku.unique'                   => 'SKU وارد شده قبلاً استفاده شده است.',
            'super_attributes.array'       => 'ویژگی‌های اصلی باید یک آرایه باشند.',
            'super_attributes.min'         => 'حداقل باید یک ویژگی اصلی انتخاب شود.',
            'super_attributes.*.array'     => 'هر ویژگی اصلی باید یک آرایه باشد.',
            'super_attributes.*.min'       => 'هر ویژگی اصلی باید حداقل دارای یک مقدار باشد.',
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
            'message' => trans('product::validation.datagiven'), // می‌توانید این پیام را ترجمه کنید.
            'error' => $firstError[0], // فقط اولین پیام خطا
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
