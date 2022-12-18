<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'source_ip',
        'source_port',
        'destination_ip',
        'destination_port',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
