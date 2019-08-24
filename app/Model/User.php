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
        'password', 'remember_token', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'rules' => [
                'user' => 'required|min:3|max:25|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'telephone' => 'required|min:8|max:11',
                'type' => 'required',
                'cep' => 'required|min:8|max:8',
                'street' => 'required|min:3|max:100',
                'number' => 'required|min:3|max:10',
                'city' => 'required|min:3|max:50',
                'state' => 'required|min:2|max:2',
                'secondary_email' => 'unique:users'
            ],
            'messages' => [
                'required' => 'Campo obrigatório',
                'min' => 'Campo inválido',
                'max' => 'Campo inválido',
                'user.unique' => 'Este usuário já está em uso',
                'email' => 'Email inválido',
                'email.unique' => 'Email em uso'
            ]
        ];
    }
}
