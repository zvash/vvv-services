<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function servers()
    {
        return $this->hasMany(Server::class);
    }
}
