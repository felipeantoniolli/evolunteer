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

    public static function insertRules()
    {
        return [
            'rules' => [
                'id_user' => 'required|unique:volunteers|unique:institutions',
                'reason' => 'required|min:3|max:150',
                'fantasy' => 'required|min:3|max:150',
                'cpf' => 'nullable|min:11|max:11|unique:volunteers|unique:institutions',
                'cnpj' => 'nullable|min:14|max:14|unique:institutions'
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function updateRules()
    {
        return [
            'rules' => [
                'id_user' => 'required',
                'reason' => 'required|min:3|max:150',
                'fantasy' => 'required|min:3|max:150',
                'cpf' => 'nullable|min:11|max:11',
                'cnpj' => 'nullable|min:14|max:14'
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function messagesRules()
    {
        return [
            'required' => 'Campo obrigatório.',
            'min' => 'Campo inválido.',
            'max' => 'Campo inválido.',
            'id_user.unique' => 'ID do usuário em uso.',
            'cpf.unique' => 'CPF em uso.',
            'cnpj.unique' => 'CNPJ em uso.'
        ];
    }

    public static function uniqueRules($req, $data)
    {
        $errors = [];

        $idUserNotUnique = Volunteer::where('id_user', $req['id_user'])
        ->where('id_user', '<>', $data->id_user)->first();

        if (!$idUserNotUnique) {
             $idUserNotUnique = Institution::where('id_user', $req['id_user'])
            ->where('id_user', '<>', $data->id_user)->first();
        }

        $cpfNotUnique = null;
        if ($req['cpf']) {
            $cpfNotUnique = Institution::where('cpf', $req['cpf'])
            ->where('id_user', '<>', $data->id_user)->first();

            if (!$cpfNotUnique) {
                $cpfNotUnique = Volunteer::where('cpf', $req['cpf'])->first();
            }
        }

        $cnpjNotUnique = null;
        if ($req['cnpj']) {
            $cnpjNotUnique = Institution::where('cnpj', $req['cnpj'])
            ->where('id_user', '<>', $data->id_user)->first();        }

        if ($idUserNotUnique) {
            $errors['id_user'] = ['ID em uso.'];
        }

        if ($cpfNotUnique) {
            $errors['cpf'] = ['CPF em uso'];
        }

        if ($cnpjNotUnique) {
            $errors['cnpj'] = ['CNPJ em uso'];
        }

        return $errors;
    }
}
