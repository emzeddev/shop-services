<?php

namespace Modules\CatalogRule\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CatalogRule\Contracts\CatalogRuleProduct as CatalogRuleProductContract;
use Modules\Core\Models\ChannelProxy;
use Modules\Customer\Models\CustomerGroupProxy;
use Modules\Product\Models\ProductProxy;

class CatalogRuleProduct extends Model implements CatalogRuleProductContract
{
    public $timestamps = false;

    protected $fillable = [
        'starts_from',
        'ends_till',
        'discount_amount',
        'action_type',
        'end_other_rules',
        'sort_order',
        'catalog_rule_id',
        'channel_id',
        'customer_group_id',
        'product_id',
    ];

    /**
     * Get the Catalog Rule that owns the catalog rule.
     */
    public function catalog_rule()
    {
        return $this->belongsTo(CatalogRuleProxy::modelClass(), 'catalog_rule_id');
    }

    /**
     * Get the Product that owns the catalog rule.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'product_id');
    }

    /**
     * Get the channels that owns the catalog rule.
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass(), 'channel_id');
    }

    /**
     * Get the customer groups that owns the catalog rule.
     */
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
    }
}
