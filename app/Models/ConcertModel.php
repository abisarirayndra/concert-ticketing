<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConcertModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'concert';
    protected $primaryKey = 'concert_id';

    protected $fillable = [
        'concert_id',
        'concert_band',
        'concert_date',
        'concert_start',
        'concert_end',
        'concert_end_status',
        'concert_location',
        'concert_price',
        'concert_quota',
        'concert_remaining_quota',
        'concert_category_id',
        'concert_banner',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
