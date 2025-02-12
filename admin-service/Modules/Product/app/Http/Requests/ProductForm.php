<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Str;
use Modules\Core\Rules\Decimal;
use Modules\Core\Rules\Slug;
use Modules\Product\Repositories\ProductAttributeValueRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Core\Rules\ProductCategoryUniqueSlug;

class ProductForm extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Max video upload size.
     *
     * @var int
     */
    protected $maxVideoFileSize;

    /**
     * Create a new form request instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    ) {
        $this->maxVideoFileSize = core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: '2048';
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $product = $this->productRepository->find($this->id);

        $this->rules = array_merge($product->getTypeInstance()->getTypeValidationRules(), [
            'sku'                  => ['required', 'unique:products,sku,'.$this->id, new Slug],
            'url_key'              => ['required', new ProductCategoryUniqueSlug('products', $this->id)],
            'images.files.*'       => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'images.positions.*'   => ['nullable', 'integer'],
            'videos.files.*'       => ['nullable', 'mimetypes:application/octet-stream,video/mp4,video/webm,video/quicktime', 'max:'.$this->maxVideoFileSize],
            'videos.positions.*'   => ['nullable', 'integer'],
            'special_price_from'   => ['nullable', 'date'],
            'special_price_to'     => ['nullable', 'date', 'after_or_equal:special_price_from'],
            'special_price'        => ['nullable', new Decimal, 'lt:price'],
            'visible_individually' => ['sometimes', 'required', 'in:0,1'],
            'status'               => ['sometimes', 'required', 'in:0,1'],
            'guest_checkout'       => ['sometimes', 'required', 'in:0,1'],
            'new'                  => ['sometimes', 'required', 'in:0,1'],
            'featured'             => ['sometimes', 'required', 'in:0,1'],
        ]);

        if (request()->images) {
            foreach (request()->images['files'] as $key => $file) {
                if (Str::contains($key, 'image_')) {
                    $this->rules = array_merge($this->rules, [
                        'images.files.'.$key => ['required', 'mimes:bmp,jpeg,jpg,png,webp'],
                    ]);
                }
            }
        }

        foreach ($product->getEditableAttributes() as $attribute) {
            if (
                in_array($attribute->code, ['sku', 'url_key'])
                || $attribute->type == 'boolean'
            ) {
                continue;
            }

            $validations = [];

            if (! isset($this->rules[$attribute->code])) {
                $validations[] = $attribute->is_required ? 'required' : 'nullable';
            } else {
                $validations = $this->rules[$attribute->code];
            }

            if (
                $attribute->type == 'text'
                && $attribute->validation
            ) {
                if ($attribute->validation === 'decimal') {
                    $validations[] = new Decimal;
                } elseif ($attribute->validation === 'regex') {
                    $validations[] = 'regex:'.$attribute->regex;
                } else {
                    $validations[] = $attribute->validation;
                }
            }

            if ($attribute->type == 'price') {
                $validations[] = new Decimal;
            }

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute) {
                    if (
                        ! $this->productAttributeValueRepository->isValueUnique(
                            $this->id,
                            $attribute->id,
                            $attribute->column_name,
                            request($attribute->code)
                        )
                    ) {
                        $fail(__('product::app.products.index.already-taken', ['name' => ':attribute']));
                    }
                });
            }

            $this->rules[$attribute->code] = $validations;
        }

        return $this->rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function messages()
    {
        return [
            'sku.required'                 => trans('product::validation.sku_required'),
            'sku.unique'                   => trans('product::validation.sku_unique'),
            'url_key.required'             => trans('product::validation.url_key_required'),
            'images.files.*.mimes'         => trans('product::validation.images_files_mimes'),
            'images.positions.*.integer'   => trans('product::validation.images_positions_integer'),
            'videos.files.*.mimetypes'     => trans('product::validation.videos_files_mimetypes'),
            'videos.files.*.max'           => trans('product::validation.videos_files_max'),
            'videos.positions.*.integer'   => trans('product::validation.videos_positions_integer'),
            'special_price_from.date'      => trans('product::validation.special_price_from_date'),
            'special_price_to.date'        => trans('product::validation.special_price_to_date'),
            'special_price_to.after_or_equal' => trans('product::validation.special_price_to_after_or_equal'),
            'special_price.nullable'       => trans('product::validation.special_price_nullable'),
            'special_price.lt'             => trans('product::validation.special_price_lt'),
            'visible_individually.required'=> trans('product::validation.visible_individually_required'),
            'visible_individually.in'      => trans('product::validation.visible_individually_in'),
            'status.required'              => trans('product::validation.status_required'),
            'status.in'                    => trans('product::validation.status_in'),
            'guest_checkout.required'      => trans('product::validation.guest_checkout_required'),
            'guest_checkout.in'            => trans('product::validation.guest_checkout_in'),
            'new.required'                 => trans('product::validation.new_required'),
            'new.in'                       => trans('product::validation.new_in'),
            'featured.required'            => trans('product::validation.featured_required'),
            'featured.in'                  => trans('product::validation.featured_in')
        ];
    }
}
