<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;
    protected $database = 'institutions';
    protected $primaryKey = 'id_institution';

    protected $fillable = [
        'id_institution',
        'id_user',
        'reason',
        'fantasy',
        'cpf',
        'cnpj'
    ];
}
