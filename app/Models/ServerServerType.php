<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServerServerType extends Pivot
{
    use HasFactory;

    protected $table = 'server_server_types';

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function serverType()
    {
        return $this->belongsTo(ServerType::class);
    }

}
