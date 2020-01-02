<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ////////////////////////    Permisos parte usuarios     ////////
        Permission::create([
            'name' => 'Crear usuarios',
            'slug' => 'usuarios.crear',
            'description' => 'Permite la creación usuarios'
        ]);

        Permission::create([
            'name' => 'Editar perfil del usuario',
            'slug' => 'usuarios.editar',
            'description' => 'Permite editar los datos del usuario'
        ]);

        Permission::create([
            'name' => 'Editar permisos usuarios',
            'slug' => 'usuarios.editarpermisos',
            'description' => 'Permite editar los permisos de los usuarios'
        ]);

        Permission::create([
            'name' => 'Listar usuarios',
            'slug' => 'usuarios.listar',
            'description' => 'Permite acceder a la lista de usuarios'
        ]);   

        Permission::create([
            'name' => 'Editar permisos del administrador',
            'slug' => 'usuarios.editarpermisosadministrador',
            'description' => 'Permite editar los permisos de administrador'
        ]);

        Permission::create([
            'name' => 'Ver detalles usuario',
            'slug' => 'usuarios.verdetalles',
            'description' => 'Permite ver los detalles del empleado.'
        ]);

        Permission::create([
            'name' => 'Borrar usuarios',
            'slug' => 'usuarios.borrar',
            'description' => 'Permite borrar usuarios.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos por usuario',
            'slug' => 'usuarios.listarpedidos',
            'description' => 'Permite listar los pedidos realizados por un usuario.'
        ]);

        Permission::create([
            'name' => 'Editar permisos sobre departamentos-campos',
            'slug' => 'usuarios.editardepartamentoscampos',
            'description' => 'Permite indicar para qué departamento y campo el usuario tiene permiso',
        ]);
        ////////////////////////////////////////////////////////////////



        ////////////////////////    Permisos parte pedidos     ////////
        Permission::create([
            'name' => 'Crear pedidos',
            'slug' => 'pedidos.crear',
            'description' => 'Permite crear un pedido.'
        ]);

        Permission::create([
            'name' => 'Editar pedidos',
            'slug' => 'pedidos.editar',
            'description' => 'Permite editar un pedido.'
        ]);

        Permission::create([
            'name' => 'Ver detalles pedidos',
            'slug' => 'pedidos.verdetalles',
            'description' => 'Permite ver los detalles de un pedido.'
        ]);

        Permission::create([
            'name' => 'Consultar el histórico del pedido',
            'slug' => 'pedidos.verhistoricos',
            'description' => 'Permite consultar el histórico de un pedido, quien lo ha solicitado, validado, cursado, etc., y en qué fechas.'
        ]);

        Permission::create([
            'name' => 'Actualizar recepción del material',
            'slug' => 'pedidos.recepcionar',
            'description' => 'Permite marcar un pedido o línea de pedido como recepcionada.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos solicitados',
            'slug' => 'pedidos.listarsolicitados',
            'description' => 'Permite listar los pedidos que estén solicitados pero aun están pendientes de revisar y aprobar.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos pendientes de recibir',
            'slug' => 'pedidos.listarpendientes',
            'description' => 'Permite listar los pedidos que estén pendientes de recibir, ya sean parcial o completamente.'
        ]);

        Permission::create([
            'name' => 'Valida un pedido solicitado',
            'slug' => 'pedidos.validar',
            'description' => 'Permite marcar un pedido solicitado como validado y quedará pendiente de cursar.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos validados',
            'slug' => 'pedidos.listarvalidados',
            'description' => 'Permite listar los pedidos que estén validados pero aun están pendientes de cursar.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos cursados',
            'slug' => 'pedidos.listarcursados',
            'description' => 'Permite listar los pedidos que estén en curso.'
        ]);

        Permission::create([
            'name' => 'Cursa un pedido validado',
            'slug' => 'pedidos.cursar',
            'description' => 'Permite marcar un pedido como cursado para que sea solicitado al proveedor.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos finalizados',
            'slug' => 'pedidos.listarfinalizados',
            'description' => 'Permite listar los pedidos que estén finalizados.'
        ]);

        Permission::create([
            'name' => 'Comunicar pedido al proveedor',
            'slug' => 'pedidos.comunicaraproveedor',
            'description' => 'Comunica el pedido al proveedor enviándole el PDF de pedido.'
        ]);

        Permission::create([
            'name' => 'Generar el documento pedido',
            'slug' => 'pedidos.generardocumentopedido',
            'description' => 'Genera el documento del pedido necesario para enviárselo al proveedor.'
        ]);

        Permission::create([
            'name' => 'Cancelar el pedido',
            'slug' => 'pedidos.cancelar',
            'description' => 'Permite cancelar el pedido siempre que esté en un estado que lo permita.'
        ]);

        Permission::create([
            'name' => 'Eliminar el pedido',
            'slug' => 'pedidos.eliminar',
            'description' => 'Permite eliminar el pedido si está en estado de "en creación" o "solicitado".'
        ]);

        Permission::create([
            'name' => 'Listar todos los pedidos',
            'slug' => 'pedidos.listartodos',
            'description' => 'Permite listar todos los pedidos que se han realizado, independientemente de como de su estado, etc.'
        ]);
        ////////////////////////////////////////////////////////////////



        ////////////////////////    Permisos parte campos     ////////
        Permission::create([
            'name' => 'Crea un nuevo campo',
            'slug' => 'campos.crear',
            'description' => 'Permite crear un nuevo campo en el que poder hacer pedidos.'
        ]);

        Permission::create([
            'name' => 'Listar campos',
            'slug' => 'campos.listar',
            'description' => 'Lista los campos que están creados en el sistema.'
        ]);

        Permission::create([
            'name' => 'Editar campos',
            'slug' => 'campos.editar',
            'description' => 'Permite editar los datos de un campo.'
        ]);

        Permission::create([
            'name' => 'Ver detalles campo',
            'slug' => 'campos.verdetalles',
            'description' => 'Permite ver los detalles de un campo.'
        ]);

        Permission::create([
            'name' => 'Editar sociedades',
            'slug' => 'campos.editarsociedades',
            'description' => 'Permite indicar/modificar las sociedades que gestionan un campo.'
        ]);
        ////////////////////////////////////////////////////////////////



        ////////////////////////    Permisos parte direccion     ////////
        Permission::create([
            'name' => 'Crear direcciones',
            'slug' => 'direcciones.crear',
            'description' => 'Permite crear direcciones de entrega para los campos.'
        ]);

        Permission::create([
            'name' => 'Editar direcciones',
            'slug' => 'direcciones.editar',
            'description' => 'Permite editar direcciones de entrega para los campos.'
        ]);

        Permission::create([
            'name' => 'Borrar direcciones',
            'slug' => 'direcciones.borrar',
            'description' => 'Permite borrar direcciones de entrega para los campos.'
        ]);

        Permission::create([
            'name' => 'Ver detalles direcciones',
            'slug' => 'direcciones.verdetalles',
            'description' => 'Permite ver los detalles de la dirección.'
        ]);
        ////////////////////////////////////////////////////////////////



        ////////////////////////    Permisos parte proveedores    ////////
        Permission::create([
            'name' => 'Crear proveedores',
            'slug' => 'proveedores.crear',
            'description' => 'Permite crear proveedores.'
        ]);

        Permission::create([
            'name' => 'Listar proveedores',
            'slug' => 'proveedores.listar',
            'description' => 'Permite listar los proveedores.'
        ]);

        Permission::create([
            'name' => 'Ver detalles proveedores',
            'slug' => 'proveedores.verdetalles',
            'description' => 'Permite ver los detalles de los proveedores.'
        ]);

        Permission::create([
            'name' => 'Editar proveedores',
            'slug' => 'proveedores.editar',
            'description' => 'Permite editar los datos de los proveedores.'
        ]);

        Permission::create([
            'name' => 'Borrar proveedores',
            'slug' => 'proveedores.borrar',
            'description' => 'Permite el borrado de los proveedores.'
        ]);

        Permission::create([
            'name' => 'Listar pedidos a proveedores',
            'slug' => 'proveedores.listarpedidos',
            'description' => 'Permite listar los pedidos realizados a un proveedor.'
        ]);
        ////////////////////////////////////////////////////////////////     

        ////////////////////////    Permisos parte categorías     ////////   
        Permission::create([
            'name' => 'Listar categorías',
            'slug' => 'categorias.listar',
            'description' => 'Permite ver el listado de categorías.'
        ]);

        Permission::create([
            'name' => 'Crear categorías',
            'slug' => 'categorias.crear',
            'description' => 'Permite crear una nueva categoría.'
        ]);

        Permission::create([
            'name' => 'Editar categorias',
            'slug' => 'categorias.editar',
            'description' => 'Permite editar los categorias creadas.'
        ]);

        Permission::create([
            'name' => 'Ver detalles',
            'slug' => 'categorias.verdetalles',
            'description' => 'Permite ver los detalles de la categoría.'
        ]);

        Permission::create([
            'name' => 'Borrar categorías',
            'slug' => 'categorias.borrar',
            'description' => 'Permite borrar una categoría.'
        ]);
        ////////////////////////////////////////////////////////////////


        ////////////////////////    Permisos parte formatos     ////////   
        Permission::create([
            'name' => 'Crear formatos',
            'slug' => 'formatos.crear',
            'description' => 'Permite crear un nuevo formato.'
        ]);

        Permission::create([
            'name' => 'Listar formatos',
            'slug' => 'formatos.listar',
            'description' => 'Permite listar los formatos creados.'
        ]);

        Permission::create([
            'name' => 'Editar formatos',
            'slug' => 'formatos.editar',
            'description' => 'Permite editar los formatos creados.'
        ]);

        Permission::create([
            'name' => 'Ver detalles',
            'slug' => 'formatos.verdetalles',
            'description' => 'Permite ver los detalles del formato.'
        ]);

        Permission::create([
            'name' => 'Borrar formatos',
            'slug' => 'formatos.borrar',
            'description' => 'Permite borrar un formato.'
        ]);
        ////////////////////////////////////////////////////////////////


        ////////////////////////    Permisos parte sociedades     ////////   
        Permission::create([
            'name' => 'Crear sociedades',
            'slug' => 'sociedades.crear',
            'description' => 'Permite crear un nueva sociedad.'
        ]);

        Permission::create([
            'name' => 'Listar sociedades',
            'slug' => 'sociedades.listar',
            'description' => 'Permite listar las sociedades creadas.'
        ]);

        Permission::create([
            'name' => 'Editar sociedades',
            'slug' => 'sociedades.editar',
            'description' => 'Permite editar las sociedades creadas.'
        ]);

        Permission::create([
            'name' => 'Ver detalles sociedades',
            'slug' => 'sociedades.verdetalles',
            'description' => 'Permite los detalles de las sociedades creadas.'
        ]);

        Permission::create([
            'name' => 'Editar campos',
            'slug' => 'sociedades.editarcampos',
            'description' => 'Permite editar/indicar qué campos gestiona la sociedad.'
        ]);
        ////////////////////////////////////////////////////////////////

        ////////////////////////    Permisos parte departamentos     ////////   
        Permission::create([
            'name' => 'Crear departamentos',
            'slug' => 'departamentos.crear',
            'description' => 'Permite crear un nuevo departamento.'
        ]);

        Permission::create([
            'name' => 'Listar departamentos',
            'slug' => 'departamentos.listar',
            'description' => 'Permite listar los departamentos creados.'
        ]);

        Permission::create([
            'name' => 'Ver detalles departamento',
            'slug' => 'departamentos.verdetalles',
            'description' => 'Permite ver los detalles de un departamento.'
        ]);

        Permission::create([
            'name' => 'Editar departamento',
            'slug' => 'departamentos.editar',
            'description' => 'Permite editar un departamento.'
        ]);

        Permission::create([
            'name' => 'Editar departamentos campo',
            'slug' => 'departamentos.editardepartamentos',
            'description' => 'Permite editar los departamentos que tiene un campo.'
        ]);

        Permission::create([
            'name' => 'Borrar departamentos',
            'slug' => 'departamentos.borrar',
            'description' => 'Permite borrar departamentos.'
        ]);

        Permission::create([
            'name' => 'Editar campos',
            'slug' => 'departamentos.editarcampos',
            'description' => 'Permite indicar qué campos tienen un departamento.'
        ]);        
        ////////////////////////////////////////////////////////////////

        ////////////////////////    Permisos parte informes     ////////
        Permission::create([
            'name' => 'Informe gastos',
            'slug' => 'informes.gastos',
            'description' => 'Permite obtener un informe sobre los gastos, ya sea por departamento, por usuarios, categoría/partida, etc.'
        ]);

        Permission::create([
            'name' => 'Informe por categorías',
            'slug' => 'informes.porcategorias',
            'description' => 'Permite obtener un informe sobre los gastos agrupados por categorías',
        ]);

        Permission::create([
            'name' => 'Informe lineas agrupados por catería',
            'slug' => 'informes.lineascategorias',
            'description' => 'Permite obtener un informe de las líneas pedido agrupadas por categoría',
        ]);
        ////////////////////////////////////////////////////////////////
    }
}
