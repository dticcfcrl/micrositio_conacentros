<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
        'usuario',
        'first_name',
        'last_name',
        'phone_number',
        'status',
        'banned',
        'email',
        'password',
    ]; */

    protected $fillable = [
        /* 'usuario', */
        'nombre',
        'apellidos',
        'email',
        'buzon',
        'no_personal',
        'email_verificado',
        'perfil',
        'password',
        'id_estado',
        'id_ccls',
        'status',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        /* 'email_verified_at' => 'datetime', */
        'email_verificado' => 'datetime',
    ];

    /* protected $appends = ['full_name']; */
    //protected $appends = ['nombre_completo'];

    public function getFullNameAttribute()
    {
        /* return $this->first_name . ' ' . $this->last_name; */
        return Str::ucfirst($this->nombre) . ' ' . Str::title($this->apellidos);
    }

    public function userProfile() {
        /* return $this->hasOne(UserProfile::class, 'user_id', 'id'); */
        return $this->hasOne(UserProfile::class, 'id_usuario', 'id');
    }
}
