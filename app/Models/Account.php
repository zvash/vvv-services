<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_to',
        'token',
        'user_id',
    ];

    protected $appends = [
        'servers'
    ];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getServersAttribute()
    {
        $links = $this->links()
            ->where('still_valid', true)
            ->get();
        $servers = [];
        foreach ($links as $link) {
            $servers[] = $link->server->country;
        }
        return implode(', ', array_unique($servers));
    }
}
