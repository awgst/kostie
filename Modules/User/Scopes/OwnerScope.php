<?php

namespace Modules\User\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Modules\User\Constants\UserType;

class OwnerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('type', UserType::OWNER);
    }
}