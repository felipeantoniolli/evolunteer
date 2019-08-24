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

    public static function rules()
    {
        return [
            'rules' => [
                'id_user' => 'required',
                'name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:100',
                'cpf' => 'required|min:11|max:11|unique:volunteers|unique:institutions',
                'rg' => 'required|min:7|max:10',
                'birth' => 'required|date'
            ],
            'messages' => [
                'required' => 'Campo obrigatório',
                'min' => 'Campo inválido',
                'max' => 'Campo inválido',
                'cpf.unique' => 'Este cpf já está em uso',
                'birth.date' => 'Data inválida'
            ]
        ];
    }
}
