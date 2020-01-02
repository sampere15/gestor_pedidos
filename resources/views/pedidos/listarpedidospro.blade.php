@extends('layouts.app')

@section('content')
	<h2>{{ $titulo }}</h2>

	<div class="row">
        <div class="col-xs-12 col-sm-12">
            <table id="tablaListadoPedidos" class="table table-condensed table-striped"  style="width: 100%">
                <thead>
                    <tr>
                    	<th width="10px">Nº</th>
                        <th>Fecha creación</th>
                        <th>Departamento</th>
	                    <th>Campo</th>
	                    <th>Proveedor</th>
	                    <th>Sociedad</th>
	                    <th>Solicitado por</th>
	                    <th>Estado Pedido</th>
	                    <th>Total Pedido</th>
	                    <th width="10px"></th>
                    </tr>
                </thead>
                <tbody>
                	@foreach ($pedidos as $pedido)
                        @if($pedido->cancelado)
                        <tr style="background-color: #f7d2bc">
                        @else
                        <tr>
                        @endif
                			<td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at }}</td>
                            <td><a href="{{ route('departamentos.verdetalles', $pedido->departamento) }}">{{ $pedido->departamento->nombre }}</a></td>
                			<td>
                				<a href="{{ route('campos.verdetalles', $pedido->campo) }}">{{ $pedido->campo->nombre }}</a>
                			</td>
                			<td>
                				<a href="{{ route('proveedores.verdetalles', $pedido->proveedor) }}">{{ $pedido->proveedor->nombre }}</a>
                			</td>
                			<td>
                				<a href="{{ route('sociedades.verdetalles', $pedido->sociedad) }}">{{ $pedido->sociedad->nombre }}</a>
                			</td>
                			<td>
                				<a href="{{ route('usuarios.verdetalles', $pedido->usuarioRealizaPedido) }}">{{ $pedido->usuarioRealizaPedido->nombre }}</a>
                            </td>
                            <td>{{ $pedido->cancelado ? "CANCELADO" : $pedido->estadoPedido->nombre }}</td>
                			<td>{{ $pedido->total_pedido }} €</td>
                			<td>
                				<a href="{{ route('pedidos.verdetalles', $pedido) }}" class="btn btn-xs btn-info">Ver detalles</a>
                			</td>
                		</tr>
                	@endforeach
                </tbody>
            </table>
        </div>
    </div>

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
                "paging":   true,
                "ordering": true,
                "info":     true,
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningún registro encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar: "
                },
                "order": [[ 1, "desc" ]],
                "searching": true,
            });
        } );
    </script>
@endpush