<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;		//	Para poder hacer transacciones a la BBDD
use App\Helpers\MiHelper;
use App\Departamento;
use App\UsuarioPermisoSobreDepartamentoCampo;
use App\EstadoPedido;

//  Con esto indicamos que el usuario va a usar el Trait de Shinobi para poder asignar/comprobar los permisos que tiene. Es decir, para que utilice la realación 0-muchos que puede tener tanto
//  para los permisos como para los roles
use Caffeinated\Shinobi\Traits\ShinobiTrait;

class User extends Authenticatable
{
    use Notifiable, ShinobiTrait;
    // use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'apellidos', 'nif', 'email', 'activo', 'password'];

    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // public function campo()
    // {
    //     // belongsTo(RelatedModel, foreignKey = campo_id, keyOnRelatedModel = id)
    //     return $this->belongsTo(Campo::class);
    // }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_realiza_pedido_id');
    }

    //  Relacion con la tabla usuario_puede_departamento_campo
    public function tienePermisoSobreDepartamentoCampo()
    {
        return $this->hasMany(UsuarioPermisoSobreDepartamentoCampo::class, 'usuario_id');
    }

    //  Indica en qué departamentos tiene el usuario permiso para operar
    public function departamentosConPermiso()
    {
        // //  Hacemos la consultado a la BBDD de los ID's de los departamentos para los que tenemos permiso
        $query = DB::select(
            "SELECT DISTINCT(departamentos.id)
            FROM departamentos, campo_departamento, campos, usuario_puede_departamento_campo
            where campo_departamento.departamento_id = departamentos.id
            and campos.id = campo_departamento.campo_id
            and usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            and usuario_puede_departamento_campo.usuario_id =" . $this->id
        );

        //  Guardamos el ID de los departamentos en un array con el que luego podremos obtener los objetos Departamento que vamos a devolver
        $arrayDepartamentos = array();
        foreach ($query as $registro) {
            array_push($arrayDepartamentos, $registro->id);
        }

        //  Ahora que tenemos los ID's simplemente recuperamos los departamentos
        $departamentos = Departamento::find($arrayDepartamentos);

        return $departamentos;
    }

    //  Indica para un departamento dado sobre qué campos tiene permiso
    public function camposConPermisoSegunDepartamento($departamento_id)
    {
        $query = DB::select(
            "SELECT DISTINCT(campos.id)
            FROM departamentos, campo_departamento, campos, usuario_puede_departamento_campo
            where campo_departamento.departamento_id = departamentos.id
            and campos.id = campo_departamento.campo_id
            and usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            and usuario_puede_departamento_campo.usuario_id =" . $this->id . " and campo_departamento.departamento_id = " . $departamento_id
        );

        //  Guardamos el ID de los campos en un array con el que luego podremos obtener los objetos Campo que vamos a devolver
        $arrayCampos = array();
        foreach ($query as $registro) {
            array_push($arrayCampos, $registro->id);
        }

        //  Ahora que tenemos los ID's simplemente recuperamos los campos
        $campos = Campo::find($arrayCampos);

        return $campos;
    }

    //  Con esta comprobación sabemos si un usuario tiene permiso para un departamento y campo dado el campo_departamento_id
    public function comprobarPermisoDepartamentoCampo($campoDepartamento_id)
    {
        $resultado = UsuarioPermisoSobreDepartamentoCampo::where('usuario_id', $this->id)->where('campo_departamento_id', $campoDepartamento_id)->count();

        if($resultado == 1)
            return true;
        else
            return false;
    }

    //  Indica en qué campos tiene el usuario permiso para operar independientemente del departamento
    public function camposConPermiso()
    {
        // //  Hacemos la consultado a la BBDD de los ID's de los campos para los que tenemos permiso
        $query = DB::select(
            "SELECT DISTINCT(campos.id)
            FROM departamentos, campo_departamento, campos, usuario_puede_departamento_campo
            where campo_departamento.departamento_id = departamentos.id
            and campos.id = campo_departamento.campo_id
            and usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            and usuario_puede_departamento_campo.usuario_id =" . $this->id
        );

        //  Guardamos el ID de los campos en un array con el que luego podremos obtener los objetos Campo que vamos a devolver
        $arrayCampos = array();
        foreach ($query as $registro) {
            array_push($arrayCampos, $registro->id);
        }

        //  Ahora que tenemos los ID's simplemente recuperamos los campos
        $campos = Campo::find($arrayCampos);

        return $campos;
    }

    //  Comprueba si un usuario tiene permiso para un departamento y campo dados sus ids
    public function comprobarDepartamentoYCampo($departamento_id, $campo_id)
    {
        $query = DB::select(
            "SELECT count(usuario_puede_departamento_campo.usuario_id) as contador
            FROM usuario_puede_departamento_campo, campo_departamento
            WHERE campo_departamento.id = usuario_puede_departamento_campo.campo_departamento_id
            AND usuario_puede_departamento_campo.usuario_id = ?
            AND campo_departamento.departamento_id = ?
            AND campo_departamento.campo_id = ?
            "
        , [$this->id, $departamento_id, $campo_id]);

        if($query[0]->contador == 1)
            return true;
        else
            return false;
    }

    //  Recupera los pedidos para los que tiene permiso el usuario según el estado del pedido
    public function pedidosSegunEstado($estado_nombre)
    {
		//  Obtenemos el ID del estado que nos han pasado
        $estado_id = EstadoPedido::where('nombre', $estado_nombre)->pluck('id')->first();
        
        //  Sólo mostremos los pedidos cuya pareja departamento-campo tenga permiso el usuario
        $query = DB::select(
            "SELECT pedidos.id 
            FROM pedidos
            WHERE pedidos.estado_pedido_id = ?
            AND pedidos.cancelado = false
            AND (pedidos.departamento_id, pedidos.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM usuario_puede_departamento_campo, campo_departamento
                WHERE usuario_puede_departamento_campo.usuario_id = ?
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)"
            , [$estado_id, $this->id]
        );

        //  Extremos todos los ID a un array para poder recuperar todos los pedidos con el metodo find
        $array_pedidos_id = array();
        foreach ($query as $registro) 
        {
            array_push($array_pedidos_id, $registro->id);
        }

        //  Ahora recuperamos todos los pedidos resultado de la query
        $pedidos = Pedido::find($array_pedidos_id);

        return $pedidos;
    }

    //  Recupera los pedidos para los que tiene el permiso según un array de estados de pedidos
    public function pedidosSegunEstados($array_estados)
    {
        $array_estados_ids = EstadoPedido::whereIn('nombre', $array_estados)->pluck('id')->toArray();   //  Recuperamos el id de los estados que nos ha llegado por parámetro
        $string_array_estados_ids = MiHelper::FormateaArrayIDsParaFiltroIN($array_estados_ids);         //  Formateamos para que queden así "(1, 2, 3)" y poder aplicarlo en el filtro IN de la query

         //  Sólo mostremos los pedidos cuya pareja departamento-campo tenga permiso el usuario
         $query = DB::select(
            "SELECT pedidos.id 
            FROM pedidos
            WHERE pedidos.estado_pedido_id IN " . $string_array_estados_ids .
            "AND (pedidos.departamento_id, pedidos.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM usuario_puede_departamento_campo, campo_departamento
                WHERE usuario_puede_departamento_campo.usuario_id = ?
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)"
            , [$this->id]
        );

        //  Extremos todos los ID a un array para poder recuperar todos los pedidos con el metodo find
        $array_pedidos_id = array();
        foreach ($query as $registro) 
        {
            array_push($array_pedidos_id, $registro->id);
        }

        //  Ahora recuperamos todos los pedidos resultado de la query
        $pedidos = Pedido::find($array_pedidos_id);

        return $pedidos;
    }
}
