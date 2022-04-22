<?php

namespace Modules\Kost\Entities;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $guarded = ['id'];

    protected $connection = 'mysql';

    protected $table = 'kosts';
}