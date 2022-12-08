<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'host',
        'scheme',
        'ip',
        'panel_port',
        'panel_username',
        'panel_password',
        'is_domestic',
    ];

    protected $appends = [
        'console_address',
        'address',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @return string
     */
    public function getConsoleAddressAttribute()
    {
        $address = $this->host ?? $this->ip;
        $address = $this->scheme . $address;
        if ($this->panel_port) {
            $address = $address . ':' . $this->panel_port;
        }
        return $address;
    }

    public function getAddressAttribute()
    {
        return $this->host ? $this->host : $this->ip;
    }
}
