<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'user_name_last',
        'user_email',
        'user_password',
        'user_role',
        'user_status',
    ];

    protected $hidden = [
        'user_password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime', // Untuk Soft Deletes
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }
}
