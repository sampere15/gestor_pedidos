@extends('layouts.app')

@section('content')
	<h2>Listar usuarios</h2>

    @can('usuarios.crear')
        <div class="row" style="margin-bottom: 20px">
            <div class="pull-right">
                <a href="{{ route('usuarios.crear') }}" class="btn btn-lg btn-success">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo usuario
                </a>
            </div>
        </div>
    @endcan

	<div class="row col-md-12 col-sm-12 col-xs-12">
		<table id="tablaListadoUsuarios" class="table table-condensed table-striped"  style="width: 100%">
            <thead>
                <tr>
                    <th>Apellidos</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    @can('usuarios.verdetalles')
                        <th width="10px"></th>
                    @endcan 
                    @if(!Auth::user()->can('usuarios.verdetalles') && Auth::user()->can('usuarios.listarpedidos'))
                        <th width="10px"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
            	@foreach ($usuarios->where('activo') as $usuario)
            		<tr>
            			<td>{{ $usuario->apellidos }}</td>
            			<td>{{ $usuario->nombre }}</td>
            			<td>{{ $usuario->email }}</td>
                        @can('usuarios.verdetalles')
                            <td><a class="btn btn-sm btn-default" href="{{ route('usuarios.verdetalles', $usuario) }}">Ver detalles</a></td>
                        @endcan
                        @if(!Auth::user()->can('usuarios.verdetalles') && Auth::user()->can('usuarios.listarpedidos'))
                            <td><a class="btn btn-sm btn-default" href="">Ver pedidos</a></td>
                        @endif
            		</tr>
            	@endforeach
            </tbody>
        </table>    
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
            $('#tablaListadoUsuarios').DataTable({
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
    </script>
@endpush
