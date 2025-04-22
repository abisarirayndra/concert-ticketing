<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketViewModel extends Model
{
    protected $table = 'v_ticket';
    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('withoutSoftDeleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}
