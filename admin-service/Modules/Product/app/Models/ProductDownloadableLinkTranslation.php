<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Contracts\ProductDownloadableLinkTranslation as ProductDownloadableLinkTranslationContract;
use Modules\Product\Database\Factories\ProductDownloadableLinkTranslationFactory;

class ProductDownloadableLinkTranslation extends Model implements ProductDownloadableLinkTranslationContract
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductDownloadableLinkTranslationFactory::new();
    }
}
