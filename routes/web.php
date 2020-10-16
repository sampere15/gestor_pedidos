<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//	Todas las rutas que esta dentro de este grupo están protegidas por el middleware que gestiona los permisos de los usuarios
Route::middleware(['auth'])->group(function(){
	//	Rutas usuarios
		Route::get('/usuarios/listar', 'UserController@listar')->name('usuarios.listar')->middleware('has.permission:usuarios.listar');
		Route::get('/usuarios/crear', 'UserController@crear')->name('usuarios.crear')->middleware('has.permission:usuarios.crear');
		Route::post('/usuarios/generar', 'UserController@generar')->name('usuarios.generar')->middleware('has.permission:usuarios.crear');
		Route::get('/usuarios/{usuario}/editar', 'UserController@editar')->name('usuarios.editar')->middleware('has.permission:usuarios.editar');
		Route::put('/usuarios/{usuario}/actualizar', 'UserController@actualizar')->name('usuarios.actualizar')->middleware('has.permission:usuarios.editar');
		Route::get('/usuarios/{usuario}/detalles', 'UserController@verdetalles')->name('usuarios.verdetalles')->middleware('has.permission:usuarios.verdetalles');
		Route::delete('/usuarios/{usuario}/borrar', 'UserController@borrar')->name('usuarios.borrar')->middleware('has.permission:usuarios.borrar');
		Route::get('/usuarios/{usuario}/listarpedidos', 'PedidoController@listarpedidosusuario')->name('usuarios.listarpedidos')->middleware('has.permission:usuarios.listarpedidos');
		Route::get('/usuarios/{usuario}/editardepartamentoscampos', 'UserController@editardepartamentoscampos')->name('usuarios.editardepartamentoscampos')->middleware('has.permission:usuarios.editardepartamentoscampos');
		Route::post('/usuarios/{usuario}/actualizardepartamentoscampos', 'UserController@actualizardepartamentoscampos')->name('usuarios.actualizardepartamentoscampos')->middleware('has.permission:usuarios.editardepartamentoscampos');
		Route::get('/usuarios/mispedidos', 'UserController@mispedidos')->name('usuarios.mispedidos');

		//	Rutas peticiones Ajax
			Route::post('/usuarios/{usuario}/campossegundepartamento/{departamento}', 'UserController@camposSegunDepartamento');
			Route::post('/usuarios/{usuario}/departamentosseguncampo/{campo}', 'UserController@departamentosSegunCampo');
		//	Fin rutas peticiones Ajax
	//////////////////

	//	Rutas pedidos
		Route::get('/pedidos/crear', 'PedidoController@crear')->name('pedidos.crear')->middleware('has.permission:pedidos.crear');
		Route::post('/pedidos/guardar', 'PedidoController@guardar')->name('pedidos.guardar')->middleware('has.permission:pedidos.crear');
		Route::get('/pedidos/{pedido}/editar', 'PedidoController@editar')->name('pedidos.editar')->middleware('has.permission:pedidos.editar');
		Route::put('/pedidos/{pedido}/actualizar', 'PedidoController@actualizar')->name('pedidos.actualizar')->middleware('has.permission:pedidos.editar');
		Route::get('/pedidos/listarsolicitados', 'PedidoController@listarsolicitados')->name('pedidos.listarsolicitados')->middleware('has.permission:pedidos.listarsolicitados');
		Route::get('/pedidos/listarvalidados', 'PedidoController@listarvalidados')->name('pedidos.listarvalidados')->middleware('has.permission:pedidos.listarvalidados');
		Route::get('/pedidos/listarpendientescomunicar', 'PedidoController@listarpendientescomunicar')->name('pedidos.listarpendientescomunicar')->middleware('has.permission:pedidos.comunicaraproveedor');
		Route::get('/pedidos/listarpendientes', 'PedidoController@listarpendientes')->name('pedidos.listarpendientes')->middleware('has.permission:pedidos.listarpendientes');
		Route::get('/pedidos/{pedido}/detalles', 'PedidoController@verdetalles')->name('pedidos.verdetalles')->middleware('has.permission:pedidos.verdetalles');
		Route::get('/pedidos/{pedido}/historico', 'PedidoController@verhistorico')->name('pedidos.verhistorico')->middleware('has.permission:pedidos.verhistoricos');
		Route::put('/pedidos/{pedido}/validar', 'PedidoController@validar')->name('pedidos.validar')->middleware('has.permission:pedidos.validar');
		Route::put('/pedidos/{pedido}/cursar', 'PedidoController@cursar')->name('pedidos.cursar')->middleware('has.permission:pedidos.cursar');
		Route::post('/pedidos/guardartemporal', 'PedidoController@guardartemporal')->name('pedidos.guardartemporal')->middleware('has.permission:pedidos.crear');
		Route::get('/pedidos/{pedido}/materialrecibido', 'PedidoController@materialrecibido')->name('pedidos.recepcionar')->middleware('has.permission:pedidos.recepcionar');
		Route::put('/pedidos/{pedido}/materialrecibido', 'PedidoController@actualizarmaterialrecibido')->name('pedidos.actualizarmaterialrecibido')->middleware('has.permission:pedidos.recepcionar');
		Route::put('/pedidos/{pedido}/comunicaraproveedor', 'PedidoController@comunicaraproveedor')->name('pedidos.comunicaraproveedor')->middleware('has.permission:pedidos.comunicaraproveedor');
		Route::get('/pedidos/listarfinalizados', 'PedidoController@listarfinalizados')->name('pedidos.listarfinalizados')->middleware('has.permission:pedidos.listarfinalizados');
		Route::get('/pedido/{pedido}/documentopedido', 'PedidoController@documentopedido')->name('pedidos.documentopedido')->middleware('has.permission:pedidos.generardocumentopedido');
		Route::get('/pedidos/mispedidosguardados', 'PedidoController@mispedidosguardados')->name('pedidos.mispedidosguardados')->middleware('has.permission:pedidos.crear');
		Route::put('/pedidos/{pedido}/cancelar', 'PedidoController@cancelar')->name('pedidos.cancelar')->middleware('has.permission:pedidos.cancelar');
		Route::put('/pedidos/{pedido}/eliminar', 'PedidoController@eliminar')->name('pedidos.eliminar')->middleware('has.permission:pedidos.eliminar');
		Route::get('/pedidos/listartodos', 'PedidoController@listartodos')->name('pedidos.listartodos')->middleware('has.permission:pedidos.listartodos');
		Route::put("/pedidos/validarvarios", "PedidoController@validarvarios")->name("pedidos.validarvarios")->middleware("has.permission:pedidos.validar");
		Route::put("/pedidos/cursarvarios", "PedidoController@cursarvarios")->name("pedidos.cursarvarios")->middleware("has.permission:pedidos.cursar");

	//	OJO, NO ESTÁ PROTEGIDA ESTA RUTA CON EL MIDDLEWARE
		Route::post('/pedidos/{pedido}/observaciones', 'PedidoController@observaciones');
	/////////////////

	//	Rutas campos
		Route::get('/campos/crear', 'CampoController@crear')->name('campos.crear')->middleware('has.permission:campos.crear');
		Route::post('/campos/crear', 'CampoController@guardar')->name('campos.guardar')->middleware('has.permission:campos.crear');
		Route::get('/campos/listar', 'CampoController@listar')->name('campos.listar')->middleware('has.permission:campos.listar');
		Route::get('/campos/{campo}/editar', 'CampoController@editar')->name('campos.editar')->middleware('has.permission:campos.editar');
		Route::get('/campos/{campo}/detalles', 'CampoController@verdetalles')->name('campos.verdetalles')->middleware('has.permission:campos.verdetalles');
		Route::put('/campos/{campo}/actualizar', 'CampoController@actualizar')->name('campos.actualizar')->middleware('has.permission:campos.editar');
		Route::post('/campos/{campo}/sociedadfavoritaysociedades', 'CampoController@sociedadfavoritaysociedades')->middleware('has.permission:pedidos.crear');
		Route::post('/campos/{campo}/direcciones', 'CampoController@direcciones')->middleware('has.permission:pedidos.crear');
		Route::get('/campos/{campo}/editarsociedades', 'CampoController@editarsociedades')->name('campos.editarsociedades')->middleware('has.permission:campos.editarsociedades');
		Route::put('/campos/{campo}/actualizarsociedades', 'CampoController@actualizarsociedades')->name('campos.actualizarsociedades')->middleware('has.permission:campos.editarsociedades');
		Route::get('/campos/{campo}/editardepartamentos', 'CampoController@editardepartamentos')->name('campos.editardepartamentos')->middleware('has.permission:campos.editardepartamentos');
		Route::put('/campos/{campo}/actualizardepartamentos', 'CampoController@actualizardepartamentos')->name('campos.actualizardepartamentos')->middleware('has.permission:campos.actualizardepartamentos');
	////////////////

	//	Rutas direcciones
		Route::get('/direcciones/{campo}/crear', 'DireccionController@crear')->name('direcciones.crear')->middleware('has.permission:direcciones.crear');
		Route::post('/direcciones/{campo}/guardar', 'DireccionController@guardar')->name('direcciones.guardar')->middleware('has.permission:direcciones.crear');
		Route::get('/direcciones/{direccion}/editar', 'DireccionController@editar')->name('direcciones.editar')->middleware('has.permission:direcciones.editar');
		Route::put('/direcciones/{direccion}/actualizar', 'DireccionController@actualizar')->name('direcciones.actualizar')->middleware('has.permission:direcciones.editar');
		Route::delete('/direcciones/{direccion}/borrar', 'DireccionController@borrar')->name('direcciones.borrar')->middleware('has.permission:direcciones.borrar');
		Route::get('/direcciones/{direccion}/detalles', 'DireccionController@detalles')->name('direcciones.verdetalles')->middleware('has.permission:direcciones.verdetalles');
	////////////////

	//	Rutas proveedores
		Route::get('/proveedores/crear', 'ProveedorController@crear')->name('proveedores.crear')->middleware('has.permission:proveedores.crear');
		Route::post('/proveedores/guardar', 'ProveedorController@guardar')->name('proveedores.guardar')->middleware('has.permission:proveedores.crear');
		Route::get('/proveedores/{proveedor}/detalles', 'ProveedorController@verdetalles')->name('proveedores.verdetalles')->middleware('has.permission:proveedores.verdetalles');
		Route::get('/proveedorse/listar', 'ProveedorController@listar')->name('proveedores.listar')->middleware('has.permission:proveedores.listar');
		Route::get('/proveedores/{proveedor}/detalles', 'ProveedorController@verdetalles')->name('proveedores.verdetalles')->middleware('has.permission:proveedores.verdetalles');
		Route::get('/proveedores/{proveedor}/editar', 'ProveedorController@editar')->name('proveedores.editar')->middleware('has.permission:proveedores.editar');
		Route::put('/proveedores/{proveedor}/actualizar', 'ProveedorController@actualizar')->name('proveedores.actualizar')->middleware('has.permission:proveedores.editar');
		Route::delete('/proveedores/{proveedor}/borrar', 'ProveedorController@borrar')->name('proveedores.borrar')->middleware('has.permission:proveedores.borrar');
		Route::get('/proveedores/{proveedor}/listarpedidos', 'PedidoController@listarpedidosproveedor')->name('proveedores.listarpedidos')->middleware('has.permission:proveedores.listarpedidos');
	////////////////

	//	Rutas categorías
		Route::get('/categorias/listar', 'CategoriaController@listar')->name('categorias.listar')->middleware('has.permission:categorias.listar');
		Route::get('/categorias/crear', 'CategoriaController@crear')->name('categorias.crear')->middleware('has.permission:categorias.crear');
		Route::post('/categorias/guardar', 'CategoriaController@guardar')->name('categorias.guardar')->middleware('has.permission:categorias.crear');
		Route::get('/categorias/{categoria}/editar', 'CategoriaController@editar')->name('categorias.editar')->middleware('has.permission:categorias.editar');
		Route::put('/categorias/{categoria}/actualizar', 'CategoriaController@actualizar')->name('categorias.actualizar')->middleware('has.permission:categorias.editar');
		Route::get('/categorias/{categoria}/detalles', 'CategoriaController@detalles')->name('categorias.verdetalles')->middleware('has.permission:categorias.verdetalles');
		Route::delete('/categorias/{categoria}/borrar', 'CategoriaController@borrar')->name('categorias.borrar')->middleware('has.permission:categorias.borrar');
	////////////////

	//	Rutas formatos
		Route::get('/formatos/listar', 'FormatoController@listar')->name('formatos.listar')->middleware('has.permission:formatos.listar');
		Route::get('/formatos/crear', 'FormatoController@crear')->name('formatos.crear')->middleware('has.permission:formatos.crear');
		Route::post('/formatos/guardar', 'FormatoController@guardar')->name('formatos.guardar')->middleware('has.permission:formatos.crear');
		Route::get('/formatos/{formato}/editar', 'FormatoController@editar')->name('formatos.editar')->middleware('has.permission:formatos.editar');
		Route::put('/formatos/{formato}/actualizar', 'FormatoController@actualizar')->name('formatos.actualizar')->middleware('has.permission:formatos.editar');
		Route::get('/formatos/{formato}/detalles', 'FormatoController@detalles')->name('formatos.verdetalles')->middleware('has.permission:formatos.verdetalles');
		Route::delete('/formatos/{formato}/borrar', 'FormatoController@borrar')->name('formatos.borrar')->middleware('has.permission:formatos.borrar');
	////////////////

	//	Rutas sociedades
		Route::get('/sociedades/listar', 'SociedadController@listar')->name('sociedades.listar')->middleware('has.permission:sociedades.listar');
		Route::get('/sociedades/crear', 'SociedadController@crear')->name('sociedades.crear')->middleware('has.permission:sociedades.crear');
		Route::post('/sociedades/guardar', 'SociedadController@guardar')->name('sociedades.guardar')->middleware('has.permission:sociedades.crear');
		Route::get('/sociedades/{sociedad}/editar', 'SociedadController@editar')->name('sociedades.editar')->middleware('has.permission:sociedades.editar');
		Route::put('/sociedades/{sociedad}/actualizar', 'SociedadController@actualizar')->name('sociedades.actualizar')->middleware('has.permission:sociedades.editar');
		Route::get('/sociedades/{sociedad}/detalles', 'SociedadController@verdetalles')->name('sociedades.verdetalles')->middleware('has.permission:sociedades.verdetalles');
		Route::get('/sociedades/{sociedad}/editarcampos', 'SociedadController@editarcampos')->name('sociedades.editarcampos')->middleware('has.permission:sociedades.editarcampos');
		Route::put('/sociedades/{sociedad}/actualizarcampos', 'SociedadController@actualizarcampos')->name('sociedades.actualizarcampos')->middleware('has.permission:sociedades.editarcampos');
	////////////////

	//	Rutas departamentos
		Route::get('/departamentos/crear', 'DepartamentoController@crear')->name('departamentos.crear')->middleware('has.permission:departamentos.crear');
		Route::post('/departamentos/crear', 'DepartamentoController@guardar')->name('departamentos.guardar')->middleware('has.permission:departamentos.crear');
		Route::get('/departamentos/listar', 'DepartamentoController@listar')->name('departamentos.listar')->middleware('has.permission:departamentos.listar');
		Route::get('/departamentos/{departamento}/editar', 'DepartamentoController@editar')->name('departamentos.editar')->middleware('has.permission:departamentos.editar');
		Route::put('/departamentos/{departamento}/actualizar', 'DepartamentoController@actualizar')->name('departamentos.actualizar')->middleware('has.permission:departamentos.editar');
		Route::get('/departamentos/{departamento}/verdetalles', 'DepartamentoController@verdetalles')->name('departamentos.verdetalles')->middleware('has.permission:departamentos.verdetalles');
		Route::delete('/departamentos/{departamento}/borrar', 'DepartamentoController@borrar')->name('departamentos.borrar')->middleware('has.permission:departamentos.borrar');
		Route::get('/departamentos/{departamento}/editarcampos', 'DepartamentoController@editarcampos')->name('departamentos.editarcampos')->middleware('has.permission:departamentos.editarcampos');
		Route::put('/departamentos/{departamento}/actualizarcampos', 'DepartamentoController@actualizarcampos')->name('departamentos.actualizarcampos')->middleware('has.permission:departamentos.editarcampos');
	///////////////////

	//	Rutas informres
		Route::get('/informes/gastos', 'InformeController@gastos')->name('informes.gastos')->middleware('has.permission:informes.gastos');
		Route::post('/informes/gastos', 'InformeController@resultadogastos')->name('informes.resultadogastos')->middleware('has.permission:informes.gastos');
		Route::get('/informes/porcategorias', 'InformeController@porcategorias')->name('informes.porcategorias')->middleware('has.permission:informes.porcategorias');
		Route::post('/informes/porcategorias', 'InformeController@resultadoporcategorias')->name('informes.resultadoporcategorias')->middleware('has.permission:informes.porcategorias');
		Route::get('/informes/lineascategorias', 'InformeController@lineascategorias')->name('informes.lineascategorias')->middleware('has.permission:informes.lineascategorias');
		Route::post('/informes/resultadolineascategorias', 'InformeController@resultadolineascategorias')->name('informes.resultadolineascategorias')->middleware('has.permission:informes.lineascategorias');
	///////////////////
});
