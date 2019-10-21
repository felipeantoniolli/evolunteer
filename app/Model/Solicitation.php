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

    public static function rules()
    {
        return [
            'rules' => [
                'id_volunteer' => 'required',
                'id_institution' => 'required',
                'message' => 'nullable',
                'approved' => 'nullable',
                'justification' => 'nullable'
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function messagesRules()
    {
        return [
            'required' => 'Campo obrigatório.',
            'min' => 'Campo inválido.',
            'max' => 'Campo inválido.'
        ];
    }
}
