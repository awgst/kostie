<?php

namespace Modules\Kost\Entities;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Kost\Database\factories\KostFactory;
use Modules\User\Entities\Owner;

class Kost extends Model
{
    use Uuid, HasFactory;
    
    protected $guarded = ['id'];

    protected $table = 'kosts';

    // Owner Relation
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    // Scope Available
    public function scopeAvailable(Builder $query)
    {
        return $query->where('slot', '>', 0);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return KostFactory::new();
    }
}