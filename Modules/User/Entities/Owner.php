<?php

namespace Modules\User\Entities;

use Modules\User\Scopes\OwnerScope;

class Owner extends User
{
    public static function boot()
    {
        static::addGlobalScope(new OwnerScope);
        parent::boot();
    }
}