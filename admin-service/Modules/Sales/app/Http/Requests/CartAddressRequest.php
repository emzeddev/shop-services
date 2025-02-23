<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\PhoneNumber;

class CartAddressRequest extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Determine if the product is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->has('billing')) {
            $this->mergeAddressRules('billing');
        }

        if (! $this->input('billing.use_for_shipping')) {
            $this->mergeAddressRules('shipping');
        }

        return $this->rules;
    }

    /**
     * Merge new address rules.
     *
     * @return void
     */
    private function mergeAddressRules(string $addressType)
    {
        $this->mergeWithRules([
            "{$addressType}.company_name" => ['nullable'],
            "{$addressType}.first_name"   => ['required'],
            "{$addressType}.last_name"    => ['required'],
            "{$addressType}.email"        => ['required'],
            "{$addressType}.address"      => ['required', 'array', 'min:1'],
            "{$addressType}.city"         => ['required'],
            "{$addressType}.country"      => ['required'],
            "{$addressType}.state"        => ['required'],
            "{$addressType}.postcode"     => ['required', 'numeric'],
            "{$addressType}.phone"        => ['required', new PhoneNumber],
        ]);
    }

    /**
     * Merge additional rules.
     */
    private function mergeWithRules($rules): void
    {
        $this->rules = array_merge($this->rules, $rules);
    }





    /**
     * Get custom error messages for validation rules.
     *
     * @return array
    */
    public function messages(): array
    {
        return [
            'billing.first_name.required' => trans("sales::validation.billing_first_name_required"),
            'billing.last_name.required'  => trans("sales::validation.billing_last_name_required"),
            'billing.email.required'      => trans("sales::validation.billing_email_required"),
            'billing.address.required'    => trans("sales::validation.billing_address_required"),
            'billing.city.required'       => trans("sales::validation.billing_city_required"),
            'billing.country.required'    => trans("sales::validation.billing_country_required"),
            'billing.state.required'      => trans("sales::validation.billing_state_required"),
            'billing.postcode.required'   => trans("sales::validation.billing_postcode_required"),
            'billing.postcode.numeric'    => trans("sales::validation.billing_postcode_numeric"),
            'billing.phone.required'      => trans("sales::validation.billing_phone_required"),

            'shipping.first_name.required' => trans("sales::validation.shipping_first_name_required"),
            'shipping.last_name.required'  => trans("sales::validation.shipping_last_name_required"),
            'shipping.email.required'      => trans("sales::validation.shipping_email_required"),
            'shipping.address.required'    => trans("sales::validation.shipping_address_required"),
            'shipping.city.required'       => trans("sales::validation.shipping_city_required"),
            'shipping.country.required'    => trans("sales::validation.shipping_country_required"),
            'shipping.state.required'      => trans("sales::validation.shipping_state_required"),
            'shipping.postcode.required'   => trans("sales::validation.shipping_postcode_required"),
            'shipping.postcode.numeric'    => trans("sales::validation.shipping_postcode_numeric"),
            'shipping.phone.required'      => trans("sales::validation.shipping_phone_required"),
        ];
    }
}

