<?php

namespace App\Repository\Test;

use App\Repository\Contract\WithFilter;
use App\Repository\Repository;
use App\Repository\Traits\WithFiltering;
use Modules\User\Entities\User;

class TestRepository extends Repository implements WithFilter
{
    use WithFiltering;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function _filters(array $request, $query)
    {
        return $query->when(array_key_exists('type', $request), function ($q) use($request) {
            return $q->where('type', $request['type']);
        });
    }
}