<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Contracts\OrderPayment as OrderPaymentContract;
use Modules\Sales\Database\Factories\OrderPaymentFactory;

class OrderPayment extends Model implements OrderPaymentContract
{
    use HasFactory;

    protected $table = 'order_payment';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return OrderPaymentFactory::new();
    }
}
