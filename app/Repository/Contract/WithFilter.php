<?php

namespace App\Repository\Contract;

interface WithFilter
{
    // Function to filter the model by request filter | data
    public function filter(array $request);
    // Function _filters to set where clause by $request data
    public function _filters(array $request, $query);
}