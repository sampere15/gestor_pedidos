<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\CampoDepartamento;

class UsuarioPermisoSobreDepartamentoCampo extends Model
{
    protected $table = 'usuario_puede_departamento_campo';
    protected $fillable = ['usuario_id', 'campo_departamento_id'];

    public $timestamps = false;

    //  Relación que tiene con la tabla de usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    //  Relación que tiene con la tqabla CampoDepartamento
    public function campoDepartamento()
    {
        return $this->belongsTo(CampoDepartamento::class);
    }
}
