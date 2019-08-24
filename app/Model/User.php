<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'user',
        'email',
        'password',
        'telephone',
        'type',
        'cep',
        'street',
        'number',
        'city',
        'state',
        'complement',
        'reference',
        'active',
        'secondary_telephone',
        'secondary_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function insertRules()
    {
        return [
            'rules' => [
                'user' => 'required|min:3|max:25|unique:users,user',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'telephone' => 'required|min:8|max:11',
                'type' => 'required',
                'cep' => 'required|min:8|max:8',
                'street' => 'required|min:3|max:100',
                'number' => 'required|min:3|max:10',
                'city' => 'required|min:3|max:50',
                'state' => 'required|min:2|max:2'
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function updateRules($request, $user)
    {
        return [
            'rules' => [
                'user' => 'required|min:3|max:25',
                'email' => 'required|email',
                'password' => 'required',
                'telephone' => 'required|min:8|max:11',
                'type' => 'required',
                'cep' => 'required|min:8|max:8',
                'street' => 'required|min:3|max:100',
                'number' => 'required|min:3|max:10',
                'city' => 'required|min:3|max:50',
                'state' => 'required|min:2|max:2'
            ],
            'messages' => self::messagesRules()
        ];
    }

    public static function messagesRules()
    {
        return [
            'required' => 'Campo obrigatório',
            'min' => 'Campo inválido',
            'max' => 'Campo inválido',
            'user.unique' => 'Este usuário já está em uso',
            'email' => 'Email inválido',
            'email.unique' => 'Email em uso',
            'secondary_email.unique' => 'Email em uso'
        ];
    }

    public static function uniqueRules($req, $user)
    {
        $errors = [];

        $userNotUnique = User::where('user', $req['user'])
        ->where('id_user', '<>', $user->id_user);

        $emailNotUnique = User::where('email', $req['email'])
        ->where('id_user', '<>', $user->id_user);

        $secondayEmailNotUnique = null;

        if ($req['secondary_email']) {
            $secondayEmailNotUnique = User::where('secondary_email', $req['seconday_email'])
            ->where('id_user', '<>', $user->id_user);
        }

        if ($userNotUnique) {
            $errors['user'] = ['Usuário em uso'];
        }

        if ($emailNotUnique) {
            $errors['email'] = ['Email em uso'];
        }

        if ($secondayEmailNotUnique) {
            $errors['secondary_email'] = ['Email em uso'];
        }

        return $errors;
    }
}
