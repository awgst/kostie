<?php

namespace Modules\Kost\Repositories;

use App\Repository\Contract\WithFilter;
use App\Repository\Contract\WithSort;
use App\Repository\Repository;
use App\Repository\Traits\WithFiltering;
use App\Repository\Traits\WithSorting;
use Modules\Kost\Entities\Kost;

class KostRepository extends Repository implements WithFilter, WithSort
{
    use WithFiltering, WithSorting;

    public function __construct(Kost $kost)
    {
        parent::__construct($kost);
    }

    /**
     * Function _filters to set where clause by $request data
     * @param array $request
     * @param mix $query
     */
    public function _filters(array $request, $query)
    {
        return $query->when((array_key_exists('name', $request) && !is_null($request['name'])), function ($q) use($request) {
                        return $q->where('name', 'like', $request['name']);
                    })->when((array_key_exists('address', $request) && !is_null($request['address'])), function ($q) use($request) {
                        return $q->where('address', 'like', $request['address']);
                    })->when((array_key_exists('price', $request) && !is_null($request['price'])), function ($q) use($request) {
                        return $q->whereBetween('price', [$request['price']]);
                    });
    }

    /**
     * Find available kost with slot > 0
     */
    public function available()
    {
        $model = $this->getModel();
        $model = $model->available();

        $this->setModel($model);
        return $this;
    }
}
