<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Contracts\Role as RoleContract;
use Modules\User\Database\Factories\RoleFactory;

class Role extends Model implements RoleContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'permission_type',
        'permissions',
    ];

    /**
     * The attributes that are castable.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the admins.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admins()
    {
        return $this->hasMany(AdminProxy::modelClass());
    }

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
