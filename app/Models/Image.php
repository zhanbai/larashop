<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
