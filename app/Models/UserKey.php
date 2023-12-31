<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserKey extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_key', 'public_key'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
