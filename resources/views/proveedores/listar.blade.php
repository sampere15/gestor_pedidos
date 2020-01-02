@extends('layouts.app')

@section('content')
	<h2>Listado de proveedores</h2>

    @can('proveedores.crear')
        <div class="row" style="margin-bottom: 20px">
            <div class="pull-right">
                <a href="{{ route('proveedores.crear') }}" class="btn btn-lg btn-success">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo proveedor
                </a>
            </div>
        </div>
    @endcan

	<div class="row col-md-12 col-sm-12 col-xs-12">
		<table id="tablaListadoProveedores" class="table table-condensed table-striped"  style="width: 100%">
            <thead>
                <tr>
                    {{-- <th width="10px"></th> --}}
                    <th>Nombre</th>
                    <th>CIF</th>
                    <th>Dirección</th>
                    <th>Provincia</th>
                    <th>País</th>
                    <th>Persona Contacto</th>
                    <th>Teléfono Contacto</th>
                    <th>Correo Contacto</th>
                    @can('proveedores.verdetalles')
                    	<th width="10px"></th>
                    @endcan
                </tr>
            </thead>
            <tbody>
        		@foreach ($proveedores->where('activo') as $proveedor)
        			<tr>
        				{{-- <td>{{ $proveedor->id }}</td> --}}
        				<td>{{ $proveedor->nombre }}</td>
        				<td>{{ $proveedor->cif }}</td>
        				<td>{{ $proveedor->direccion }}</td>
        				<td>{{ $proveedor->provincia }}</td>
        				<td>{{ $proveedor->pais }}</td>
        				<td>{{ $proveedor->persona_contacto }}</td>
        				<td>{{ $proveedor->telefono_contacto }}</td>
        				<td>{{ $proveedor->correo_contacto }}</td>
        				@can('proveedores.verdetalles')
	                    	<td><a class="btn btn-sm btn-default" href="{{ route('proveedores.verdetalles', $proveedor) }}">Ver detalles</a></td>
	                    @endcan
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
                "order": [[ 1, "asc" ]],
                "searching": true,
            });
        } );
    </script>
@endpush