<?php

namespace Modules\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Attribute\Database\Factories\AttributeFactory;
use Modules\Attribute\Contracts\Attribute as AttributeContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model implements AttributeContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'admin_name',
        'type',
        'enable_wysiwyg',
        'position',
        'is_required',
        'is_unique',
        'validation',
        'regex',
        'value_per_locale',
        'value_per_channel',
        'default_value',
        'is_filterable',
        'is_configurable',
        'is_visible_on_front',
        'is_user_defined',
        'swatch_type',
        'is_comparable',
    ];


    /**
     * Get the options.
     */
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOptionProxy::modelClass());
    }


    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }
}
