<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicKey extends Model
{
    use HasFactory;

    protected $fillable = ['public_key', 'public_IV'];
}
