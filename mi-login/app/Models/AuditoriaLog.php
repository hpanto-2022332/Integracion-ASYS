<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';

    protected $fillable = [
        'user_id',
        'usuario_nombre',
        'accion',
        'url',
        'metodo',
        'ip',
        'detalles_query',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
