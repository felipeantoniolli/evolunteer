<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitation extends Model
{
    use SoftDeletes;

    protected $database = 'solicitations';
    protected $primaryKey = 'id_solicitation';

    protected $fillable = [
        'id_solicitation',
        'id_volunteer',
        'id_institution',
        'message',
        'approved',
        'justification'
    ];
}
