<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'server_id',
        'url',
        'has_tls',
        'tunneled',
        'limit',
        'consumer_count',
        'setting_ps',
        'setting_id',
        'setting_port',
        'setting_add',
        'setting_tls',
        'setting_path',
        'is_proxy',
    ];

    protected $appends = [
        'iptables_commands',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function buildURL()
    {
        $settings = [
            'v' => '2',
            'ps' => $this->setting_ps,
            'add' => $this->setting_add,
            'port' => $this->setting_port,
            'id' => $this->setting_id,
            'aid' => 0,
            'net' => 'tcp',
            'type' => 'none',
            'host' => '',
            'path' => $this->setting_path ?? '',
            'tls' => $this->setting_tls,
        ];

        return 'vmess://' . base64_encode(json_encode($settings));
    }

    public function getIptablesCommandsAttribute()
    {
        $commands = [];
        $server = $this->server;
        if ($server->remote_server) {
            $commands[] = "sudo iptables -t nat -A PREROUTING -p tcp --dport {$this->setting_port} -j DNAT --to-destination {$server->remote_server}:{$this->setting_port}";
            $commands[] = "sudo iptables -I INPUT -p tcp --dport {$this->setting_port} -j ACCEPT";
            return implode("\n", $commands);
        }
        return null;
    }
}
