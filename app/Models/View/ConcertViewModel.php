<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcertViewModel extends Model
{
    protected $table = 'v_concert';
    protected $primaryKey = 'concert_id';
    public $incrementing = false;
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('withoutSoftDeleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}
