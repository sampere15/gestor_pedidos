@extends('layouts.app')

@section('content')
{{--     <div class="col-md-12 col-sm-12">
        <div id="resultadoOperacion" class="alert alert-success" role="alert" hidden="true">Mensaje de resultado</div>
    </div> --}}

	<div class="row">
		<div class="col-sm-6 col-md-6">
			<h2>Detalles del pedido {{ $pedido->id }}</h2>
            <a href="{{ URL::previous() }}">
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Atrás</button>
            </a>
		</div>
		<div class="col-sm-6 col-md-6" style="text-align: right;">
			@if(!$pedido->cancelado)
                <h2>Estado del pedido: <span style="color: {{ $pedido->estadoPedido->color }}">{{ $pedido->estadoPedido->nombre_mostrar }}</span></h2>
            @else
                <h2 style="color: red;">PEDIDO CANCELADO</h2>
            @endif
            <div class="pull-right">
                {{-- @can('pedidos.verhistorico')
                    <a class="btn btn-default" href="{{ route('pedidos.verhistorico', $pedido) }}">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Histórico
                    </a>
                @endcan --}}
                {{-- @if($pedido->estadoPedido->nombre == "solicitado" && Auth::user()->can('pedidos.validar'))
                    <button class="btn btn btn-success" data-toggle="modal" data-target="#modalValidarCursarPedido">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Validar pedido
                    </button>
                @elseif($pedido->estadoPedido->nombre == "validado" && Auth::user()->can('pedidos.cursar'))
                    <button class="btn btn btn-success" data-toggle="modal" data-target="#modalValidarCursarPedido">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Cursar pedido
                    </button>
                @endif --}}
                @if(!$pedido->cancelado)
                    {{-- Si el pedido no está cancelado lo podremos validar o cursar --}}
                    @if($pedido->estadoPedido->nombre == "solicitado" && Auth::user()->can('pedidos.validar'))
                        <button class="btn btn btn-success" data-toggle="modal" data-target="#modalValidarCursarPedido">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Validar pedido
                        </button>
                    @elseif($pedido->estadoPedido->nombre == "validado" && Auth::user()->can('pedidos.cursar'))
                        <button class="btn btn btn-success" data-toggle="modal" data-target="#modalValidarCursarPedido">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Cursar pedido
                        </button>
                    @endif

                    @if(Auth::user()->can('pedidos.editar') 
                    && ($pedido->estadoPedido->nombre == "solicitado") || $pedido->estadoPedido->nombre == "en_creacion")
                        <a class="btn btn-warning" href="{{ route('pedidos.editar', $pedido) }}">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar pedido
                        </a>
                    @elseif(Auth::user()->isRole('administrador'))
                        <a class="btn btn-warning" href="{{ route('pedidos.editar', $pedido) }}">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar pedido como Administrador
                        </a>
                    @endif
                    @if($pedido->HistoricoTieneEstado("cursado") && Auth::user()->can('pedidos.generardocumentopedido'))
                        <a class="btn btn-default" href="{{ route('pedidos.documentopedido', $pedido) }}" target="_blank" onclick="DocumentoPedidoGenerado()">
                            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Documento del pedido
                        </a>
                    @endif
                    @if(Auth::user()->can('pedidos.comunicaraproveedor') && (!$pedido->pedido_comunicado && $pedido->HistoricoTieneEstado("cursado")) && $pedido->documento_visualizado)
                        <button type="button" class="btn btn-success" id="btnComunicadoProveedor" data-toggle="modal" data-target="#modaComunicadoProveedor">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Comunicado al proveedor
                        </button>
                    @endif
                    @if(Auth::user()->can('pedidos.recepcionar') && ($pedido->estadoPedido->nombre == "recibido_parcialmente" || $pedido->estadoPedido->nombre == "pendiente_recibir"))
                        <a class="btn btn-warning" href="{{ route('pedidos.recepcionar', $pedido) }}">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Material recibido
                        </a>
                    @endif
                    @if(Auth::user()->can('pedidos.cancelar') && $pedido->estadoPedido->nombre != "finalizado")
                        <button href="" class="btn btn-danger" data-toggle="modal" data-target="#modalCancelarPedido">
                            @if($pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion")
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Borrar
                            @else
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Cancelar
                            @endif
                        </button>
                    @endif
                @endif
            </div>
		</div>
	</div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-3 col-sm-3">
                <h3>Pedido para campo: <small>{{ $pedido->campo->nombre }}</small></h3>
            </div>
            <div class="col-md-3 col-sm-3">
                <h3>Proveedor: <small>{{ $pedido->proveedor->nombre }}</small></h3>
            </div>
            <div class="col-md-3 col-sm-3">
                <h3>Solicita el pedido: <small>{{ $pedido->usuarioRealizaPedido->nombre }}</small></h3>
            </div>
            <div class="col-md-3 col-sm-3">
                <h3>Fecha creación: <small>{{ $pedido->created_at }}</small></h3>
            </div>
        </div>
    </div>

    @if($pedido->cancelado)
        <div class="row" style="margin-top: 20px;">
            <u style="color: red"><h3>Motivo cancelación:</h3></u>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <textarea readonly class="form-control">{{ $pedido->motivo_cancelacion }}</textarea>
            </div>
        </div>
    @endif

	<div class="row" style="margin-top: 20px">
        <div class="col-md-12 table-responsive">
            <table id="tablaListadoLineasPedido" class="table table-condensed table-striped" style="width: 100%">
                <thead>
                    <tr>
                    	<th>Línea</th>
                    	<th>Categoría</th>
                    	<th>Descripción</th>
                    	<th>Unidades</th>
                		<th>Formato</th>
                		<th>Precio</th>
                		<th>Total</th>
                        <th>Estado Línea</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lineasPedido as $linea)
                        <tr>
                        	<td>{{ $linea->numero_linea }}</td>
                        	<td>{{ $linea->Categoria->nombre }}</td>
                        	<td>{{ $linea->descripcion }}</td>
                        	<td>{{ $linea->unidades }}</td>
                        	<td>{{ $linea->Formato->nombre }}</td>
                        	<td>{{ number_format($linea->precio, 2, ",", ".") }} €</td>
                        	<td>{{ number_format($linea->precio * $linea->unidades, 2, ",", ".") }} €</td>
                            <td>{{ $linea->estadoLinea->nombre }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr style="border-style: inset; border-width: 1px;">
            <div class="col-md-3 col-sm-3 pull-right" style="text-align: right;">
            	<b>Total Pedido: </b>{{ number_format($totalPedido, 2, ",", ".") }} €
            </div>
        </div>
    </div>
    <hr style="border-style: inset; border-width: 1px;">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-sx-12">
            <h3 style="color: #39bfc9">Estados Especiales</h3>
            <div class="form-group">
                <label>
                    <input type="radio" id="rbSolicitadoProveedor" name="rbEstadosEspeciales" value="rbSolicitadoProveedor" {{ $pedido->solicitado_al_proveedor ? "checked" : ""}} disabled> Se ha contactado con el proveedor para que marche el pedido
                </label>
            </div>
            <div class="form-group">
                <label>
                    <input type="radio" id="rbMaterialRecibido" name="rbEstadosEspeciales" value="rbMaterialRecibido" {{ $pedido->ya_recibido ? "checked" : ""}} disabled> Ya se ha recibido el material
                </label>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-sx-12" style="text-align: center">
            @if(!$pedido->cancelado)
                @if($pedido->solicitado_al_proveedor)
                    <p id="mensaje_alerta" style="font-size: 200%">¡El pedido ya se ha solicitado al proveedor, tan sólo hay que comunicarlo! Pero que no lo vuelva a mandar!!</p>
                @elseif($pedido->ya_recibido)
                    <p id="mensaje_alerta" style="font-size: 200%">¡El pedido ya se ha recibido, tan sólo hay que comunicarlo! Pero que no lo vuelva a mandar!!</p>
                @endif
            @else
                <div class="col-md-8 col-sm-8 col-md-offset-4 col-sm-offset-4 col-xs-12" style="text-align: center">
                    <img src="{{ asset('images/cancelado.png') }}" class="img-responsive">
                </div>
            @endif
        </div>
    </div>
    <hr style="border-style: inset; border-width: 1px;">
    <div class="row">
        <div class="form-group col-md-6 col-sm-6 col-xs-6">
            <label for="observaciones">Observaciones:</label>
            <textarea class="form-control" rows="5" readonly>{{ $pedido->observaciones }}</textarea>
        </div>
        @can('pedidos.verhistoricos')
            {{-- <div class="col-md6 col-sm-6 col-xs-6">
                <h4 style="text-align: center">Histórico</h4>
                <hr style="border-style: inset; border-width: 1px;">
                @foreach ($estados as $estado)
                    <div class="col-md-offset-2 col-sm-offset-2 col-md-3 col-sm-3">{{ $estado->nombre_mostrar }}</div>
                    <div class="col-md-1 col-sm-1"><button class="btn" style="background-color: {{ $estado->color }}" {{ $estado->nombre != $pedido->estadoPedido->nombre ? " disabled" : "" }}></button>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        @foreach ($pedido->historicoEstados as $historico)
                            @if($historico->estado == $estado->nombre)
                                {{ $historico->fecha }} ({{ $historico->usuario->nombre }})
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div> --}}
            <div class="col-md6 col-sm-6 col-xs-6">
                <h4 style="text-align: center">Histórico</h4>
                <hr style="border-style: inset; border-width: 1px;">
                @foreach ($estados as $estado)
                    {{-- El estado recibido_parcialmente sólo lo mostraremos si el pedido ha pasado por ahí --}}
                    @if($estado->nombre == "recibido_parcialmente" && !$pedido->HistoricoTieneEstado("recibido_parcialmente"))
                        @continue
                    @endif
                    @if($pedido->HistoricoTieneEstado($estado->nombre))
                        <div class="col-md-5 col-sm-5">
                            <b>
                               @if($estado->nombre == $pedido->estadoPedido->nombre && $estado->nombre != "finalizado")
                                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                @endif
                                {{ $estado->nombre_mostrar }}
                            </b>
                        </div>
                        <div class="col-md-1 col-sm-1"><button class="btn btn-sm" style="background-color: {{ $estado->color }}"></button></div>
                        <div class="col-md-6 col-sm-6">
                            {{ $pedido->HistoricoTieneEstado($estado->nombre)->fecha }} ({{ $pedido->HistoricoTieneEstado($estado->nombre)->usuario->nombre }})
                        </div>
                    @else
                        <div class="col-md-5 col-sm-5">{{ $estado->nombre_mostrar }}</div>
                        <div class="col-md-1 col-sm-1"><button class="btn btn-sm"></button></div>
                        <div class="col-md-6 col-sm-6">-</div>
                    @endif
                    @if($pedido->estadoPedido->nombre == "finalizado" && $estado->nombre == "finalizado")
                        <div class="col-md-12 col-sm-12" style="text-align: center"><h3>Pedido finalizado</h3></div>
                    @endif
                @endforeach
            </div>
        @endcan
    </div>
    <div class="row col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6" style="border: 1px solid #ccc; border-radius: 16px;">
            <h3>Datos de entrega</h3>
            <div class="col-md-12 col-sm-12">
                <label>Dirección:</label>
                {{ $pedido->direccion->calle }}
            </div>
            <div class="col-md-12 col-sm-12"">
                <div class="col-md-7 col-sm-7" style="padding: 0px">
                    <label>Ciudad:</label>
                    {{ $pedido->direccion->ciudad }}
                </div>
                <div class="col-md-5 col-sm-5" style="padding: 0px">
                    <label>Cód. Postal:</label>
                    {{ $pedido->direccion->codigo_postal }}
                </div>
            </div>
            <div class="col-md-12 col-sm-12"">
                <div class="col-md-7 col-sm-7" style="padding: 0px">
                    <label>Provincia:</label>
                    {{ $pedido->direccion->provincia }}
                </div>
                <div class="col-md-5 col-sm-5" style="padding: 0px">
                    <label>País:</label>
                    {{ $pedido->direccion->pais }}
                </div>
            </div>
            <div class="col-md-12 col-sm-12"">
                <div class="col-md-7 col-sm-7" style="padding: 0px">
                    <label>Persona contacto:</label>
                    {{ $pedido->direccion->persona_contacto }}
                </div>
                <div class="col-md-5 col-sm-5" style="padding: 0px">
                    <label>Teléfono contacto:</label>
                    {{ $pedido->direccion->numero_contacto }}
                </div>
            </div>
        </div>
    </div>

    @if( (Auth::user()->can('pedidos.validar') && $pedido->estadoPedido->nombre == "solicitado") 
        || (Auth::user()->can('pedidos.cursar') && $pedido->estadoPedido->nombre == "validado"))
        <!-- Modal -->
        <div class="modal fade" id="modalValidarCursarPedido" tabindex="-1" role="dialog" aria-labelledby="modalValidarCursarPedidoLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @if($pedido->estadoPedido->nombre == "solicitado")
                            <h4 class="modal-title" id="modalValidarCursarPedidoLabel">Validar pedido</h4>
                        @elseif($pedido->estadoPedido->nombre == "validado")
                            <h4 class="modal-title" id="modalValidarCursarPedidoLabel">Cursar pedido</h4>
                        @endif
                    </div>
                    <div class="modal-body">
                        @if($pedido->estadoPedido->nombre == "solicitado")
                            ¿Confirmas que quieres validar el pedido?
                        @elseif($pedido->estadoPedido->nombre == "validado")
                            ¿Confirmas que quieres cursar el pedido el pedido?
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" onclick="ValidarCursarPedido()">Sí</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario para enviar la información al controlador y que el pedido pase a estar comunicado --}}
        {{-- <form method="POST" id="formularioValidarCursarPedido" action="{{ route('pedidos.comunicaraproveedor', $pedido) }}"> --}}
        @if($pedido->estadoPedido->nombre == "solicitado")
            <form method="POST" id="formularioValidarCursarPedido" action="{{ route('pedidos.validar', $pedido) }}">
        @elseif($pedido->estadoPedido->nombre == "validado")
            <form method="POST" id="formularioValidarCursarPedido" action="{{ route('pedidos.cursar', $pedido) }}">
        @endif
            @csrf
            @method('PUT')
        </form>
    @endif

    @if(Auth::user()->can('pedidos.comunicaraproveedor') && !$pedido->pedido_comunicado)
        <!-- Modal -->
        <div class="modal fade" id="modaComunicadoProveedor" tabindex="-1" role="dialog" aria-labelledby="modaComunicadoProveedorLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modaComunicadoProveedorLabel">Pedido comunicado</h4>
                    </div>
                    <div class="modal-body">
                        ¿Confirmas que el pedido ha sido comunicado al proveedor?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" onclick="ComunicarPedido()">Sí, ha sido comunicado</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario para enviar la información al controlador y que el pedido pase a estar comunicado --}}
        <form method="POST" id="formularioComunicarAProveedor" action="{{ route('pedidos.comunicaraproveedor', $pedido) }}">
            @csrf
            @method('PUT')
        </form>
    @endif

    @can('pedidos.cancelar')
        {{-- Modal para confirmar el cancelar pedido --}}
        <div class="modal fade" id="modalCancelarPedido" tabindex="-1" role="dialog" aria-labelledby="modalCancelarPedido">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">
                            ¿Seguro que quiere {{ $pedido->estadoPedido->nombre == "solicitado" ? "borrar" : "cancelar" }} el pedido?
                        </h3>
                    </div>
                    @if($pedido->estadoPedido->nombre != "solicitado" && $pedido->estadoPedido->nombre != "en_creacion")
                        <div class="modal-body">
                            <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                            <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No! No lo {{ $pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion" ? "borres" : "canceles" }}!!
                        </button>
                        <button type="button" onclick="CancelarPedido()" class="btn btn-danger">
                            Confirmar {{ $pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion" ? "el borrado" : "la cancelación" }} del pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <form method="POST" id="formularioCancelarPedido" action="{{ route('pedidos.cancelar', $pedido) }}"> --}}
            @if($pedido->estadoPedido->nombre == "en_creacion" || $pedido->estadoPedido->nombre == "solicitado")
                <form method="POST" id="formularioCancelarPedido" action="{{ route('pedidos.eliminar', $pedido) }}">
            @else
                <form method="POST" id="formularioCancelarPedido" action="{{ route('pedidos.cancelar', $pedido) }}">
            @endif
            @csrf
            @method('PUT')
            <input type="hidden" id="inputMotivoCancelacion" name="inputMotivoCancelacion">
        </form>
    @endcan
@endsection

@push('footscripts')
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
        var colores = ["red", "black"];     //  Colores que va a tomar el mensaje de alerta
        var tams = ["200%", "200%"];
        var estado_alerta = 0;              //  Para saber que color, tamaño hay que darle al mensaje
        var recargar_pagina = false;        //  Para saber si debemos recargar la página o no

        // var estado = "{{ $pedido->estadoPedido->nombre }}";
        // console.log(estado);

        //  Cuando la página esté cargada
        $(document).ready(function() 
        {
            //  Formateamos la tabla de pedidos
            $('#tablaListadoLineasPedido').DataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                "pageLength": 100,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningún registro encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                },
                "order": [[ 0, "asc" ]],
                "searching": false,
            });
        });

        //  Cuando recuperamos el focus comprobamos si tienemos que recargar la página o no
        $(window).focus(function()
        {
            if(recargar_pagina)
            {
                location.reload();
            }

        });

        //  Sólo si cumple uno de estos dos estados mostraremos el mensaje y tu ataque epiléptico
        if({{ $pedido->solicitado_al_proveedor }} || {{ $pedido->ya_recibido }})
            setInterval(MensajeAlertaEpileptico, 1000);

        //  Cambia el color y tamaño del mensaje de alerta
        function MensajeAlertaEpileptico()
        {
            var mensaje = document.getElementById("mensaje_alerta");
            mensaje.style.fontSize = tams[estado_alerta];
            mensaje.style.color = colores[estado_alerta];

            //  Cambiamos el estado de la variable
            if(estado_alerta == 1)
                estado_alerta = 0;
            else
                estado_alerta = 1;
        }

        //  Hace el submit del formulario que envía la información para validar o cursar un pedido
        function ValidarCursarPedido()
        {
            $('#formularioValidarCursarPedido').submit();   
        }

        //  Hace el submit del formulario que envía la información para marcar un pedido como comunicado
        function ComunicarPedido()
        {
            $('#formularioComunicarAProveedor').submit();
        }

        function CancelarPedido()
        {
            @if($pedido->estadoPedido->nombre != "solicitado" && $pedido->estadoPedido->nombre != "en_creacion")
                document.getElementById("inputMotivoCancelacion").value = document.getElementById("taMotivoCancelacion").value;
            @endif
            $('#formularioCancelarPedido').submit();
        }

        //  Función con la que recargamos la página una vez haya generado el documento para que aparezca el botón con el que indicamos que el pedido ha sido comunicado al proveedor
        function DocumentoPedidoGenerado()
        {
            if(document.getElementById("btnComunicadoProveedor") == null)
                recargar_pagina = true;
        }
    </script>
@endpush
