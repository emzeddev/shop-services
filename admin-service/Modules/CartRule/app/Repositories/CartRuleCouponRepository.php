<?php

namespace Modules\CartRule\Repositories;

use Modules\Core\Eloquent\Repository;

class CartRuleCouponRepository extends Repository
{
    /**
     * @var array
     */
    protected $charset = [
        'alphanumeric' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        'alphabetical' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'numeric'      => '0123456789',
    ];

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\CartRule\Contracts\CartRuleCoupon';
    }

    /**
     * Creates coupons for cart rule
     */
    public function generateCoupons(array $data, int $cartRuleId): void
    {
        $cartRule = app('Modules\CartRule\Repositories\CartRuleRepository')->findOrFail($cartRuleId);

        for ($i = 0; $i < $data['coupon_qty']; $i++) {
            parent::create([
                'cart_rule_id'       => $cartRuleId,
                'code'               => $data['code_prefix'].$this->getRandomString($data['code_format'], $data['code_length']).$data['code_suffix'],
                'usage_limit'        => $cartRule->uses_per_coupon ?? 0,
                'usage_per_customer' => $cartRule->usage_per_customer ?? 0,
                'is_primary'         => 0,
                'expired_at'         => $cartRule->ends_till ?: null,
            ]);
        }
    }

    /**
     * Creates coupons for cart rule
     */
    public function getRandomString(string $format, int $length): string
    {
        $couponCode = '';

        for ($i = 0; $i < $length; $i++) {
            $couponCode .= $this->charset[$format][rand(0, strlen($this->charset[$format]) - 1)];
        }

        return $couponCode;
    }
}
