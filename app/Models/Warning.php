<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $fillable = [
        'user_id',
        'warned_by',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warner()
    {
        return $this->belongsTo(User::class, 'warned_by');
    }
}
