<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	//	Anes de hacer el seed borramos primero todas las tablas para evitar duplicidad y errores de unique
    	$arrayTablas = ['permissions', 'roles', 'users', 'role_user', 'permission_role', 'permission_user', 'campos', 'sociedades', 'direcciones', 'proveedores', 'categorias', 'formatos', 'estados_pedidos', 'pedidos', 'estados_lineas', 'lineas_pedidos', 'historico_estados_pedidos', 'campo_sociedad', 'departamentos', 'campo_departamento', 'usuario_puede_departamento_campo'];

    	$this->truncate_tables($arrayTablas);

    	$this->call(PermisosTableSeeder::class);
    	$this->call(RolesTableSeeder::class);
        $this->call(SociedadesTableSeeder::class);
        $this->call(CamposTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(DireccionesTableSeeder::class);
        $this->call(ProveedoresTableSeeder::class);
        $this->call(CategoriasTableSeeder::class);
        // $this->call(DescripcionesTableSeeder::class);
        $this->call(FormatosTableSeeder::class);
        $this->call(EstadosPedidosTableSeeder::class);
        $this->call(PedidosTableSeeder::class);
        $this->call(EstadosLineaTableSeeder::class);
        $this->call(LineasPedidosTableSeeder::class);
        $this->call(HistoricosEstadosPedidosTableSeeder::class);
        $this->call(DepartamentosTableSeeder::class);
        $this->call(CampoDepartamentoTableSeeder::class);
        $this->call(UsuarioPermisoSobreDepartamentoCampoSeeder::class);
    }

    //	Hace el truncate de tabla que le indiquemos
    protected function truncate_tables(array $tablas)
    {
    	//	No tiene en cuenta las claves ajenas, para evitar problemas al borrar la tabla por sus referencias
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($tablas as $tabla) {
            DB::table($tabla)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
