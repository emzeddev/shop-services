<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\ProductCategoryUniqueSlug;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
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
        $locale = core()->getRequestedLocaleCode();

        $rules = [
            'position'      => 'required',
            'logo_path'     => 'array',
            'logo_path.*'   => 'mimes:bmp,jpeg,jpg,png,webp',
            'banner_path'   => 'array',
            'banner_path.*' => 'mimes:bmp,jpeg,jpg,png,webp',
            'attributes'    => 'required|array',
            'attributes.*'  => 'required',
        ];

        if ($id = $this->id) {
            $rules[$locale.'.slug'] = ['required', new ProductCategoryUniqueSlug('category_translations', $id)];
            $rules[$locale.'.name'] = ['required'];
            $rules[$locale.'.description'] = 'required_if:display_mode,==,description_only,products_and_description';

            return $rules;
        }

        $rules['slug'] = ['required', new ProductCategoryUniqueSlug];
        $rules['name'] = 'required';
        $rules['description'] = 'required_if:display_mode,==,description_only,products_and_description';

        return $rules;
    }

    public function messages()
    {
        $locale = core()->getRequestedLocaleCode();

        return [
            'position.required'       => __('validation.position_required'),
            'logo_path.array'         => __('validation.logo_path_array'),
            'logo_path.*.mimes'       => __('validation.logo_invalid_format'),
            'banner_path.array'       => __('validation.banner_path_array'),
            'banner_path.*.mimes'     => __('validation.banner_invalid_format'),
            'attributes.required'     => __('validation.attributes_required'),
            'attributes.*.required'   => __('validation.each_attribute_required'),

            // پیام‌های اعتبارسنجی مربوط به locale
            "{$locale}.slug.required" => __('category::app.validations.slug_required'),
            "{$locale}.name.required" => __('category::app.validations.name_required'),
            "{$locale}.description.required_if" => __('category::app.validations.description_required_if'),

            // پیام‌های عمومی
            'slug.required'           => __('category::app.validations.slug_required'),
            'name.required'           => __('category::app.validations.name_required'),
            'description.required_if' => __('category::app.validations.description_required_if'),
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
