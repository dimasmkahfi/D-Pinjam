<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'lvl_users',
        'face_id',
        'face_registered_at',
        'face_image_path',
        'face_encoding',
        'verification_status'
    ];

    protected $hidden = [
        'password',
    ];
}
