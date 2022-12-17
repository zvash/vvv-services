<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftEtherAccount extends Model
{
    use HasFactory;

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
