<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestKey extends Model
{
    use HasFactory;

    protected $fillable = ['user_id_owner', 'user_id_req', 'file_id', 'status', 'symmetricKey'];

    public function user_owner()
    {
        return $this->belongsTo(User::class, 'user_id_owner');
    }

    public function user_req()
    {
        return $this->belongsTo(User::class, 'user_id_req');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
