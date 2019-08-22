<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use SoftDeletes;

    protected $database = 'works';
    protected $primaryKey = 'id_work';

    protected $fillable = [
        'id_work',
        'id_institution',
        'name',
        'content',
        'work_date'
    ];
}
