@extends('layouts.app')

@section('content')

	<h2>Listado de campos</h2>

	@can('campos.crear')
		<div class="row" style="margin-bottom: 20px">
			<div class="pull-right">
				<a href="{{ route('campos.crear') }}" class="btn btn-lg btn-success">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo Campo
				</a>
			</div>
		</div>
	@endcan

	<div class="row">
		<div class="row col-md-12 col-sm-12 col-xs-12">
			<table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 100%">
	            <thead>
	                <tr>
	                	<th></th>
	                    <th>Nombre</th>
	                    <th>Abreviatura</th>
	                    @can('campos.verdetalles')
	                    	<th></th>
	                    @endcan
	                </tr>
	            </thead>
	            <tbody>
	            	@foreach ($campos as $campo)
	            		<tr>
	            			<td>{{ $campo->id }}</td>
	            			<td>{{ $campo->nombre }}</td>
	            			<td>{{ $campo->abreviatura }}</td>
	            			@can('campos.verdetalles')
		                    	<td width="10px"><a class="btn btn-sm btn-default" href="{{ route('campos.verdetalles', $campo) }}">Ver detalles</a></td>
		                    @endcan
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
            $('#tablaListadoCampos').DataTable({
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
                "order": [[ 0, "asc" ]],
                "searching": true,
            });
        } );
    </script>
@endpush