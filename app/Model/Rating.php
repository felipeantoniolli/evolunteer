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

    public static function rules()
    {
        return [
            'rules' => [
                'id_user' => 'required',
                'id_volunteer' => 'nullable',
                'id_institution' => 'nullable',
                'name' => 'required|min:3|max:50',
                'note' => 'required',
                'message' => 'nullable|max:255'
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

    public static function requiredRules($req)
    {
        $errors = [];

        if (!$req['id_volunteer'] || $req['id_institution']) {
            $errors['id'] = "É necessário inserir um id_volunteer ou id_institution";
        }

        return $errors;
    }
}
