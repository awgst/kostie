<?php

namespace Modules\User\Entities;

use Modules\Kost\Entities\Kost;
use Modules\User\Constants\UserType;
use Modules\User\Scopes\OwnerScope;

class Owner extends User
{
    // Kost relation
    public function kosts()
    {
        return $this->hasMany(Kost::class, 'owner_id', 'id');
    }

    public static function boot()
    {
        static::addGlobalScope(new OwnerScope);
        static::creating(function ($model) {
            $model->type = UserType::OWNER;
        });
        parent::boot();
    }
}