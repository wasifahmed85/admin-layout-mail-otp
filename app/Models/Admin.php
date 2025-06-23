<?php

namespace App\Models;

use App\Models\AuthBaseModel;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends AuthBaseModel
{
        use HasFactory, Notifiable;
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
    protected $guard = 'admin';
    protected $fillable = [
        'sort_order',
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'status',
        'email_verified_at',
        'image',

        'created_by',
        'updated_by',
        'deleted_by',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
