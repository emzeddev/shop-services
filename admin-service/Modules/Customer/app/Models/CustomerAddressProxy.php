<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Customer\Database\Factories\CustomerAddressProxyFactory;

class CustomerAddressProxy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CustomerAddressProxyFactory
    // {
    //     // return CustomerAddressProxyFactory::new();
    // }
}
