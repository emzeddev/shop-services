<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Contracts\SubscribersList as SubscribersListContract;
use Modules\Core\Database\Factories\SubscriberListFactory;
// use Modules\Customer\Models\CustomerProxy;

class SubscribersList extends Model implements SubscribersListContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'subscribers_list';

    /**
     * Fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'is_subscribed',
        'token',
        'customer_id',
        'channel_id',
    ];

    /**
     * Hide the token attribute to the model.
     *
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * Get the customer associated with the subscription.
     */
    public function customer()
    {
        // return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SubscriberListFactory::new();
    }
}
