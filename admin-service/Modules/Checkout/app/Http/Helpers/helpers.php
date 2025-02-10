<?php

if (! function_exists('cart')) {
    /**
     * Cart helper.
     *
     * @return \Modules\Checkout\Cart
     */
    function cart()
    {
        return app()->make('cart');
    }
}