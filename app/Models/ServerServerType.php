<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServerServerType extends Pivot
{
    use HasFactory;

    protected $table = 'server_server_types';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function servers()
    {
        return $this->belongsToMany(Server::class, 'server_server_types', 'server_type_id', 'server_id')
            ->using(ServerServerType::class);
    }
}
