<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends Model
{
    use SoftDeletes;
    protected $database = 'interests';
    protected $primaryKey = 'id_interest';

    protected $fillable = [
        'id_interest',
        'id_user',
        'type'
    ];

    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at', 'deleted_at'
    ];
}
