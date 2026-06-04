<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}