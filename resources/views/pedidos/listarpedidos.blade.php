@extends('layouts.app')

@section('content')
    <div class="col-md-12 col-sm-12">
        <div id="resultadoOperacion" class="alert alert-success" role="alert" hidden="true">Mensaje de resultado</div>
    </div>

    <h2>{{ $titulo }}</h2>
    
    @if($tipoPedidos == "solicitados" || $tipoPedidos == "validados")
        <form id="form-validar-cursar-varios" action="{{ $tipoPedidos == "solicitados" ? route("pedidos.validarvarios") : route("pedidos.cursarvarios") }}" method="POST">
            @csrf
            @method("PUT")
    @endif

	<div class="row">
        <div class="col-xs-12 col-sm-12">
            <button type="button" class="btn btn-lg btn-success" id="btn-accion-todos" style="display: none">
                @if($tipoPedidos == "solicitados")
                    Validar todos los pedidos marcados
                @elseif($tipoPedidos == "validados")
                    Cursar todos los pedidos marcados
                @endif
            </button>
            <table id="tablaListadoPedidos" class="table table-condensed table-striped"  style="width: 100%">
                <thead>
                        @if($tipoPedidos == "solicitados" || $tipoPedidos == "validados")
                            <th width="10px"><input type="checkbox" onclick="CheckboxTodosPulsado(this);"></th>
                        @endif
                        <th>Nº</th>
                        <th width="12%">Fecha creación</th>
                        <th>Departamento</th>
                        <th>Campo</th>
                        <th width="15%">Proveedor</th>
                        <th>Sociedad</th>
                        <th width="15%">Solicitado por</th>
                        @if($tipoPedidos == "pendientes" || $tipoPedidos == "mis_pedidos")
                            <th>Estado Pedido</th>
                        @endif
                        <th>Total Pedido</th>
                        @can('pedidos.verdetalles')
                            <th width="10px"></th>
                        @endcan
                        @if( ($tipoPedidos == "solicitados" && Auth::user()->can('pedidos.validar')) 
                            || $tipoPedidos == "validados" && Auth::user()->can('pedidos.cursar') 
                            || $tipoPedidos == "pendientes" && Auth::user()->can('pedidos.recepcionar'))
                            <th width="10px"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        @if($pedido->cancelado)
                        <tr style="background-color: #f7d2bc">
                        @else
                        <tr>
                        @endif
                            @if($tipoPedidos == "solicitados" || $tipoPedidos == "validados")
                                <td><input type="checkbox" class="cb-pedido" name="pedidos[]" value="{{ $pedido->id }}" onclick="CheckboxMarcado();"></td>
                            @endif
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at }}</td>
                            <td>{{ $pedido->departamento->nombre }}</td>
                            <td>{{ $pedido->campo->nombre }}</td>
                            <td>{{ $pedido->proveedor->nombre }}</td>
                            {{-- Vamos a limitar los caracteres a 20 --}}
                            <td>
                                @if(strlen($pedido->sociedad->nombre) > 20)
                                    {{ substr($pedido->sociedad->nombre, 0, 20) }}...
                                @else
                                    {{ $pedido->sociedad->nombre }}
                                @endif
                            </td>
                            <td>{{ $pedido->usuarioRealizaPedido->nombre }}</td>
                            @if($tipoPedidos == "pendientes" || $tipoPedidos == "mis_pedidos")
                                <td>{{ $pedido->cancelado ? "CANCELADO" : $pedido->estadoPedido->nombre_mostrar }}</td>
                            @endif
                            <td>{{ $pedido->total_pedido }} €</td>
                            @can('pedidos.verdetalles') 
                                <td>
                                    <a href="{{ route('pedidos.verdetalles', $pedido) }}" class="btn btn-sm btn-info">Ver detalles</a>
                                </td>
                            @endcan
                            @if($tipoPedidos == "solicitados" && Auth::user()->can('pedidos.validar'))
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success btnCambiarEstado" id="btnCambiarEstado{{ $pedido->id }}" data-pedido_id="{{ $pedido->id }}">
                                        Validar pedido
                                    </button>
                                </td>
                            @elseif($tipoPedidos == "validados" && Auth::user()->can('pedidos.cursar') && !$pedido->cancelado)
                                <td>
                                    <button type="button" class="btn btn-sm btn-success btnCambiarEstado" id="btnCambiarEstado{{ $pedido->id }}" data-pedido_id="{{ $pedido->id }}">
                                        Cursar pedido
                                    </button>
                                </td>
                            @elseif($tipoPedidos == "pendientes" && Auth::user()->can('pedidos.recepcionar'))
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('pedidos.recepcionar', $pedido) }}">
                                        Material recibido
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($tipoPedidos == "solicitados" || $tipoPedidos == "validados")
        </form>
    @endif

    @if(Auth::user()->can('pedidos.validar') || Auth::user()->can('pedidos.verdetalles'))
        <!-- Modal para validar el pedido -->
        <div class="modal fade" id="modalCambiarEstadoPedido" tabindex="-1" role="dialog" aria-labelledby="modalCambiarEstadoPedido">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @if($tipoPedidos == "solicitados")
                            <h4>¿Está seguro de validar el pedido?</h4>
                        @elseif($tipoPedidos == "validados")
                            <h4>¿Está seguro de cursar el pedido?</h4>
                        @endif
                    </div>
                    <div class="modal-body">
                        <div id="div-textarea">
                            <h3>Observaciones del pedido</h3>
                            <textarea class="form-control" id="taObservaciones" rows="5" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No validar todavía</button>
                        @if($tipoPedidos == "solicitados")
                            <button type="button" class="btn btn-success" onclick="confirmarCambioEstadoPedido()">Confirmar validación</button>
                        @elseif($tipoPedidos == "validados")
                            <button type="button" class="btn btn-success" onclick="confirmarCambioEstadoPedido()">Confirmar para su curso</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('footscripts')
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
        //  Cuando la página esté cargada
        $(document).ready(function() 
        {
            //  Formateamos la tabla de pedidos
            $('#tablaListadoPedidos').DataTable({
                "paging":   false,
                "ordering": true,
                "info":     false,
                // "pageLength": 25,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningún registro encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar: "
                },
                "order": [[ 1, "asc" ]],
                "searching": true,
            });
        } );


        var pedido_id = -1;                     //  Aquí guardaremos el id del pedido sobre el que hemos pinchado
        var tipoPedidos = '{{ $tipoPedidos }}'; //  Aqui guardamos si estamos en el listado de pedidos solicitados o validados
        var row;                                //  Row del pedido sobre el que hemos pinchado 

        $('.btnCambiarEstado').click(function()
        {
            pedido_id = $(this).attr('data-pedido_id');
            //  Ruta con la que obtenemos la información sobre las observaciones del pedido
            var ruta = '{{ url('/pedidos') }}' + '/' + pedido_id + '/observaciones';
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
                    if(!datos)
                    {
                        MostrarMensajeAlertaResultadoOperacion('alert alert-danger', 'Ha ocurrido un error, vuelva a intentarlo más tarde');
                    }
                    else
                    {
                        if(datos['observaciones'] == null)
                            document.getElementById("taObservaciones").value = "Este pedido no tiene observaciones";
                        else
                            document.getElementById("taObservaciones").value = datos['observaciones'];
                    }
                },
                error: function(estado, errorThrown)
                {
                    console.log(estado);
                    console.log(errorThrown);
                    MostrarMensajeAlertaResultadoOperacion('alert alert-danger', 'Se ha obtenido un error en la espera de respuesta, póngase en contacto con el administrador');
                }
            });

            $('#modalCambiarEstadoPedido').modal('show');
        });

        function confirmarCambioEstadoPedido()
        {
            ruta = "";

            $('#modalCambiarEstadoPedido').modal('hide');       //  Escondemos el modal

            //  Preparamos la ruta a la que mandar la peticicón
            if(tipoPedidos == "solicitados")
                ruta = '{{ url('/pedidos') }}' + '/' + pedido_id + '/validar';
            else if(tipoPedidos == "validados")
                ruta = '{{ url('/pedidos') }}' + '/' + pedido_id + '/cursar';

            //  Mandamos la consulta
            $.ajax({
                url: ruta,
                type: "PUT",
                dataType: "json",
                data: 
                {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(datos, estado)
                {
                    if(!datos)
                    {
                        console.log("no hay datos");
                        MostrarMensajeAlertaResultadoOperacion('alert alert-danger', 'Ha ocurrido un error, vuelva a intentarlo más tarde');
                    }
                    else
                    {
                        console.log(datos);
                        if(datos['estado'] == "Exito")
                        {
                            //  Mostramos mensaje de exito
                            MostrarMensajeAlertaResultadoOperacion('alert alert-success', datos['mensaje']);
                            //  Desactivamos el botón de validar y le cambiamos el texto para indicar que ya ha sido validado
                            $('#btnCambiarEstado' + pedido_id).attr('disabled', 'true');

                            //  Cambiamos el icono del boton
                            $('#btnCambiarEstado' + pedido_id).html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                        }
                    }
                },
                error: function(estado, errorThrown)
                {
                    console.log(estado);
                    console.log(errorThrown);
                    MostrarMensajeAlertaResultadoOperacion('alert alert-danger', 'Se ha obtenido un error en la espera de respuesta, póngase en contacto con el administrador');
                }
            });
        }

        //  Función con la que mostramos temporalmente un mensaje por pantalla indicando si la operación se ha realizado con éxito o no
        function MostrarMensajeAlertaResultadoOperacion(alertclass, mensaje)
        {
            obj = document.getElementById('resultadoOperacion');
            $('#resultadoOperacion').removeClass();
            $('#resultadoOperacion').addClass(alertclass);
            $('#resultadoOperacion').html(mensaje);
            $('#resultadoOperacion').show(1000).delay(2000).hide(500);
        }

        //  Es llamado cuando pulsamos sobre el checkbox de seleccionar todos los pedidos
        function CheckboxTodosPulsado(elemento)
        {
            if(elemento.checked)
            {
                document.getElementById("btn-accion-todos").style.display = "block";
                MarcarDescarmarCheckboxes(true);
            }
            else
            {
                document.getElementById("btn-accion-todos").style.display = "none";
                MarcarDescarmarCheckboxes(false);
            }
        }

        //  Para validar varios pedidos pero no todos
        function CheckboxMarcado()
        {
            //  Comprobamos si hay algún pedido marcado, para mostrar u ocultar el botón
            $cbpedidos = document.getElementsByClassName("cb-pedido");

            for($i = 0; $i < $cbpedidos.length; $i++)
            {
                //  Si hay algún pedido marcado mostraremos el botón y saldremos de la función
                if($cbpedidos[$i].checked)
                {
                    document.getElementById("btn-accion-todos").style.display = "block";
                    return;
                }
            }

            //  Si hemos llegado a este punto es que no hay ningún checkbox marcado y por lo tanto ocultaremos el botón en caso de que estuviera visible
            document.getElementById("btn-accion-todos").style.display = "none";
        }

        //  Marca o desmarca todos los checkboxes de la lista de pedidos
        function MarcarDescarmarCheckboxes(valor)
        {
            $cbpedidos = document.getElementsByClassName("cb-pedido");
            
            for($i = 0; $i < $cbpedidos.length; $i++)
            {
                $cbpedidos[$i].checked = valor;
            }
        }

        //  Envía el formulario para validarcursar varios pedidos
        $("#btn-accion-todos").click(function()
        {
            document.getElementById("form-validar-cursar-varios").submit();
        });
        
    </script>
@endpush