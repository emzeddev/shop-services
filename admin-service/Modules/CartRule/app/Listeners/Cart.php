<?php

namespace Modules\CartRule\Listeners;

use Modules\CartRule\Helpers\CartRule;

class Cart
{
    /**
     * Create a new listener instance.
     *
     * @param  \Modules\CartRule\Repositories\CartRule  $cartRuleHelper
     * @return void
     */
    public function __construct(protected CartRule $cartRuleHelper) {}

    /**
     * Apply valid cart rules to cart
     *
     * @param  \Modules\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function applyCartRules($cart)
    {
        $this->cartRuleHelper->collect($cart);
    }
}
