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

    public static function rules()
    {
        return [
            'rules' => [
                'id_institution' => 'required',
                'name' => 'required|min:3|max:255',
                'work_date' => 'required|date_format:Y-m-d H:i:s'
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
