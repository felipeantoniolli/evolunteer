<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $database = 'volunteers';
    protected $primaryKey = 'id_volunteer';

    protected $fillable = [
        'id_volunteer',
        'id_user',
        'name',
        'last_name',
        'cpf',
        'rg',
        'birth',
        'gender'
    ];
}
