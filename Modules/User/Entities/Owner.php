<?php

namespace Modules\User\Entities;

use Modules\User\Constants\UserType;
use Modules\User\Scopes\OwnerScope;

class Owner extends User
{
    public static function boot()
    {
        static::addGlobalScope(new OwnerScope);
        static::creating(function ($model) {
            $model->type = UserType::OWNER;
        });
        parent::boot();
    }
}