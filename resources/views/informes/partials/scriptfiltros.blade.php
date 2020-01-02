{{-- JavaScript para la tabla que nos permite buscar los proveedores  --}}
{{-- borrar --}}
{{-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script> --}}
{{-- borrar --}}

{{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script type="text/javascript">

    var departamento_seleccionado_id = 0;
    var campo_seleccionado_id = 0;
    var departamentos_sin_filtro = {!! $departamentos !!};  //  Guardamos todos los departamentos para los que tiene permiso el usuario. Así los podremos restablecer si limpiamos el filtro
    var campos_sin_filtro = {!! $campos !!};                //  Guardamos todos los campos para los que tiene permiso el usuario. Así los podremos restablecer si limpiamos el filtro
    var hay_filtros_departamento_campo = false;

    $(document).ready(function() 
    {
        //  Formateamos la tabla de pedidos
        $('#tablaListadoProveedores').DataTable({
            "paging":   false,
            "ordering": true,
            "info":     false,
            "pageLength": 25,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Ningún registro encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar: "
            },
            "order": [[ 0, "asc" ]],
            "searching": true,
        });

        //  Esto es una trampilla. Ya que desde Javascript no consigo comprobar si se ha pasado cierta variable desde el backoffice a la vista (variable de Laravel-blade)
        //  Compruebo si hay un elemento con ese ID el cual sólo existirá si existe esa variable. Cosa que comprobamos en Blade
        if( document.getElementById("hay_filtros") != null)
        {
            //  Recuperamos el valor del departamento y campo. Si no se había aplicado filtro tendrán valor de 0
            departamento_seleccionado_id = document.getElementById("departamentoSelect").value;
            campo_seleccionado_id = document.getElementById("campoSelect").value;

            hay_filtros_departamento_campo = true;

            //  Si sólo está el departamento seleccionado o sólo el campo, aplicaremos el filtro al otro campo
            if(departamento_seleccionado_id != 0 && campo_seleccionado_id == 0)
            {
                console.log('entra 1');
                ActualizarCampos();
            }
            else if(campo_seleccionado_id != 0 && departamento_seleccionado_id == 0)
            {
                console.log('entra 2');
                ActualizarDepartamentos();
            }
            //  En este caso tanto el departamento como el campo vienen seleccionados. Vamos a eliminar del select todas las opciones menos "Sin filtro" y la seleccionada
            else
            {
                console.log("entra 3");
                // var departamentoSelect = document.getElementById("departamentoSelect");
                // var campoSelect = document.getElementById("campoSelect");

                var opcionesDepartamento = document.getElementById("departamentoSelect").options;
                var opcionesCampo = document.getElementById("campoSelect").options;
                
                //  Limpiamos el select de los departamentos
                for(var i = 0; i < opcionesDepartamento.length; i++)
                {
                    if(opcionesDepartamento[i].value != 0 && opcionesDepartamento[i].value != departamento_seleccionado_id)
                    {
                        departamentoSelect.remove(i);
                        i--;    //  Reducimos el índice en 1 porque como acabamos de quitar un elemento la lista "ha corrido", entonces si no reducimos el índice cuando vuelva a iniciar el bucle aumentara y nos saltaremos el siguiente indice
                    }
                }

                //  Limpiamos el select de los campos
                for(var i = 0; i < opcionesCampo.length; i++)
                {
                    if(opcionesCampo[i].value != 0 && opcionesCampo[i].value != campo_seleccionado_id)
                    {
                        campoSelect.remove(i);
                        i--;    //  Reducimos el índice en 1 porque como acabamos de quitar un elemento la lista "ha corrido", entonces si no reducimos el índice cuando vuelva a iniciar el bucle aumentara y nos saltaremos el siguiente indice
                    }
                }
            }
        }
        
    } );

    //	Cuando pulsamos en el input del proveedor mostramos el modal dónde podremos realizar búscaquedas
    $('#btnBuscarProveedor').click(function()
    {
        $('#selectorProveedorModal').modal('show');
    });

    //	Confirma la selección del proveedor en el modal de búsqueda
    $('.btnSeleccionarProveedor').click(function()
    {
        var proveedor_id = $(this).attr('data-proveedor_id');
        // console.log('proveedor seleccionado - ' + proveedor_id);
        $('#selectorProveedorModal').modal('hide');
        $('#btnBuscarProveedor').attr('value', $(this).attr('data-proveedor_nombre'));
        $('#proveedor_id').attr('value', proveedor_id);
    });

    //  Limpia el input sobre el que se ha pinchado
    function clearInput(nombreInput)
    {
        if(nombreInput == "btnBuscarProveedor")
        {
            document.getElementById("btnBuscarProveedor").removeAttribute("value");
            document.getElementById("proveedor_id").value = "";
        }
        else
            document.getElementById(nombreInput).value = "";
    }

    //  Limpia un input de tipo select del formulario
    function resetSelect(nombreSelect)
    {
        document.getElementById(nombreSelect).selectedIndex = 0;
    }

    //  Limpia todos los inputs del formulario a la vez
    function ResetFormulario()
    {
        console.log('reset');

        //  Reseteamos todos los inputs del formulario
        if(document.getElementById("dateFechaInicio") != null)
            document.getElementById("dateFechaInicio").value = "";

        if(document.getElementById("dateFechaFin") != null)
            document.getElementById("dateFechaFin").value = "";

        //  El input del proveedor no se borra con la acción anterior
        if(document.getElementById("btnBuscarProveedor") != null)
        {
            document.getElementById("btnBuscarProveedor").value = "";
            document.getElementById("proveedor_id").value = "";
        }

        //  Volvemos a cargar todos los departamentos y campos con permisos para el usuario
        if(document.getElementById("departamentoSelect") != null)
            ActualizarSelectDepartamentos(departamentos_sin_filtro);

        if(document.getElementById("campoSelect") != null)
            ActualizarSelectCampos(campos_sin_filtro);

        if(document.getElementById("categoriaSelect") != null)
            document.getElementById("categoriaSelect").selectedIndex = 0;

        if(document.getElementById("socieadSelect") != null)
            document.getElementById("socieadSelect").selectedIndex = 0;
    }
    
    //  Se le llama cuando pinchamos sobre un departamento. Recogerá el ID del departamento y llamará a la función que cargará los campos según tenga permiso con ese departamento
    function DepartamentoSeleccionado()
    {
        //  Sólo actualizaremos los campos si el filtro de campos no esta ya previamente seleccionado
        if(campo_seleccionado_id == 0)
        {
            departamento_seleccionado_id = document.getElementById("departamentoSelect").value;
            //  Recuperamos los campos para los que tiene permiso con ese departamento
            ActualizarCampos();
        }
    }

    //  Se le llama cuando pinchamos sobre un campo. Recogerá el ID del campo y llamará a la función que cargará los departamentos según tenga permiso con ese campo
    function CampoSeleccionado()
    {
        //  Sólo actualizaremos los departamentos si el filtro de departamentos no esta ya previamente seleccionado
        if(departamento_seleccionado_id == 0)
        {
            campo_seleccionado_id = document.getElementById("campoSelect").value;
            //  Recuperamos los departamentos para los que tiene permiso con ese campo
            ActualizarDepartamentos();
        }
    }

    //  Recupera los campos para los que tiene permiso según el departamento seleccionado
    function ActualizarCampos()
    {
        //  Ruta que recupera los campos
        var ruta = '{{ url('/usuarios') }}';
        ruta += "/" + {{ $usuario->id }} + "/campossegundepartamento/" + departamento_seleccionado_id;
        $.ajax({
            url: ruta,
            type: "POST",
            dataType: "json",
            data: 
            {
                "_token": "{{ csrf_token() }}",
            },
            success: function(datos, estado)
            {
                var campos = datos["campos"];
                ActualizarSelectCampos(campos);
            },
            error: function(estado, errorThrown)
            {
                console.log(estado);
                console.log(errorThrown);
            }
        });
    }

    //  Recupera los Departamentos para los que tiene permiso según el campo seleccionado
    function ActualizarDepartamentos()
    {
        //  Ruta que recupera los campos
        var ruta = '{{ url('/usuarios') }}';
        ruta += "/" + {{ $usuario->id }} + "/departamentosseguncampo/" + campo_seleccionado_id;
        $.ajax({
            url: ruta,
            type: "POST",
            dataType: "json",
            data: 
            {
                "_token": "{{ csrf_token() }}",
            },
            success: function(datos, estado)
            {
                var departamentos = datos["departamentos"];
                ActualizarSelectDepartamentos(departamentos);
            },
            error: function(estado, errorThrown)
            {
                console.log(estado);
                console.log(errorThrown);
            }
        });
    }

    //	Actualiza el select del campo con los campos para los que tenga permiso según el departamento seleccionado
    function ActualizarSelectCampos(campos)
    {
        //	Recuperamos el desplegable de los campos y vaciamos su HTML (sus opction)
        var campoSelect = document.getElementById("campoSelect");
        campoSelect.innerHTML = "";

        var html = "";		//	Aquí vamos a guardar el html que le meteremos al select

        html += "<option value='' selected='true'>Sin filtro</option>";
        for(var i = 0; i < campos.length; i++)
        {
            html += "<option value='" + campos[i]['id'] + "'>" + campos[i]['nombre'] + "</option>";
        }

        campoSelect.innerHTML = html;
    }

    //	Actualiza el select del departamento con los departamentos para los que tenga permiso según el campo seleccionado
    function ActualizarSelectDepartamentos(departamentos)
    {
        //	Recuperamos el desplegable de los departamentos y vaciamos su HTML (sus opction)
        var departamentoSelect = document.getElementById("departamentoSelect");
        departamentoSelect.innerHTML = "";

        var html = "";		//	Aquí vamos a guardar el html que le meteremos al select

        html += "<option value='' selected='true'>Sin filtro</option>";
        for(var i = 0; i < departamentos.length; i++)
        {
            html += "<option value='" + departamentos[i]['id'] + "'>" + departamentos[i]['nombre'] + "</option>";
        }

        departamentoSelect.innerHTML = html;
    }

    //  Resetea el select de los departamentos
    function ResetSelectDepartamentos()
    {
        //  Seleccionamos la primera opción del select que es "Sin filtro"
        document.getElementById("departamentoSelect").selectedIndex = 0;
        //  Indicamos que ya no tenemos ningún departamento seleccionado. Esto también nos va a servir saber en qué momento tenemos que cargar los departamentos y campos por defecto porque ya estén filtrados
        departamento_seleccionado_id = 0;

        //  Al tener un departamento ya seleccionado se habrán filtrado los campos disponibles para ese departamento. Al borrar ese filtro debemos de volver a cargar los campos con los de "por defecto" para el usuario
        if(campo_seleccionado_id == 0)
        {
            console.log("volvemos a cargar todos los campos");
            ActualizarSelectCampos(campos_sin_filtro);
        }
        else if(campo_seleccionado_id != 0 && hay_filtros_departamento_campo)
        {
            console.log("debemos aplicar el filtro a los departamentos según el campo seleccionado");
            ActualizarDepartamentos();
        }
    }

    //  Resetea el select de los campos
    function ResetSelectCampos()
    {
        //  Seleccionamos la primera opción del select que es "Sin filtro"
        document.getElementById("campoSelect").selectedIndex = 0;
        //  Indicamos que ya no tenemos ningún campo seleccionado. Esto también nos va a servir saber en qué momento tenemos que cargar los departamentos y campos por defecto porque ya estén filtrados
        campo_seleccionado_id = 0;

        //  Al tener un campo ya seleccionado se habrán filtrado los departamentos disponibles para ese departamento. Al borrar ese filtro debemos de volver a cargar los departamentos con los de "por defecto" para el usuario
        if(departamento_seleccionado_id == 0)
        {
            console.log("volvemos a cargar todos los departamentos");
            ActualizarSelectDepartamentos(departamentos_sin_filtro);
        }
        else if(departamento_seleccionado_id != 0 && hay_filtros_departamento_campo)
        {
            console.log("debemos aplicar filtro sobre los campos según el departamento seleccionado");
            ActualizarCampos();
        }
    }

</script>