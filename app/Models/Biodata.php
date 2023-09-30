<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biodata extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'gender', 'nationality', 'religion', 'marital_status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
