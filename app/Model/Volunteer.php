<?php

namespace App\Model;

use App\Http\Controllers\GeneralController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Institution;

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

    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at', 'deleted_at'
    ];

    public static function insertRules()
    {
        return [
            'rules' => [
                'name' => 'required|min:3|max:50',
                'last_name' => 'required|min:2|max:100',
                'cpf' => 'required|min:11|max:11|unique:volunteers|unique:institutions',
                'rg' => 'nullable|min:8|max:10|unique:volunteers',
                'birth' => 'required|date',
                'gender' => 'required|min:1|max:1',
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function updateRules()
    {
        return [
            'rules' => [
                'name' => 'required|min:3|max:50',
                'last_name' => 'required|min:2|max:100',
                'cpf' => 'required|min:11|max:11',
                'rg' => 'nullable|min:8|max:10',
                'birth' => 'required|date',
                'gender' => 'required|min:1|max:1'
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
            'cpf.unique' => 'CPF em uso.'
        ];
    }

    public static function validDocuments($req)
    {
        $cpfIsValid = true;
        $cpfIsValid = GeneralController::validCpf($req['cpf']);

        $errors = [];

        if (!$cpfIsValid) {
            $errors['cpf'] = ['CPF inválido'];
        }

        return $errors;
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

        $cpfNotUnique = Volunteer::where('cpf', $req['cpf'])
        ->where('id_user', '<>', $data->id_user)->first();

        if (!$cpfNotUnique) {
            $cpfNotUnique = Institution::where('cpf', $req['cpf'])->first();
        }

        $rgNotUnique = null;
        if (isset($req['rg'])) {
            $rgNotUnique = Volunteer::where('rg', $req['rg'])
            ->where('id_user', '<>', $data->id_user)->first();        }

        if ($idUserNotUnique) {
            $errors['id_user'] = ['ID em uso.'];
        }

        if ($cpfNotUnique) {
            $errors['cpf'] = ['CPF em uso'];
        }

        if ($rgNotUnique) {
            $errors['rg'] = ['RG em uso'];
        }

        if (!$errors) {
            $errors = User::validDocuments($req);
        }

        return $errors;
    }
}
