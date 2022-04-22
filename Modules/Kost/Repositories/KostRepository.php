<?php

namespace Modules\Kost\Repositories;

use App\Repository\Repository;
use Modules\Kost\Entities\Kost;

class KostRepository extends Repository
{
    public function __construct(Kost $kost)
    {
        parent::__construct($kost);
    }
}