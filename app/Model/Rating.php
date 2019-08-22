<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;

    protected $database = 'ratings';
    protected $primaryKey = 'id_rating';

    protected $fillable = [
        'id_rating',
        'id_user',
        'id_volunteer',
        'id_institution',
        'note',
        'message'
    ];
}
