<?php

namespace App\Model\Sistema;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PerfilUsuario extends Model
{
    
    protected $table = 'perfil_usuario';
    
    public $fillable = [
       'perfil_id',
       'usuario_id'
    ];
}
