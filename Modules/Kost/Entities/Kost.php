<?php

namespace Modules\Kost\Entities;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Owner;

class Kost extends Model
{
    use Uuid;
    
    protected $guarded = ['id'];

    protected $connection = 'mysql';

    protected $table = 'kosts';

    // Owner Relation
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }
}