@extends('layouts.app')

@section('content')

	<h2>Listado de formatos</h2>

	@can('formatos.crear')
		<div class="row" style="margin-bottom: 20px">
			<div class="pull-right">
				<a href="{{ route('formatos.crear') }}" class="btn btn-lg btn-success">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo formato
				</a>
			</div>
		</div>
	@endcan

	<div class="row col-md-12 col-sm-12 col-xs-12">
		<table id="tablaListadoFormatos" class="table table-condensed table-striped"  style="width: 20%">
            <thead>
                <tr>
                    <th width="10px"></th>
                    <th>Nombre</th>
                    @can('formatos.editar')
                    	<th width="10px"></th>
                    @endcan
                </tr>
            </thead>
            <tbody>
        		@foreach ($formatos->where('activo') as $formato)
        			<tr>
        				<td>{{ $formato->id }}</td>
        				<td>{{ $formato->nombre }}</td>
        				@can('formatos.verdetalles')
	                    	<td>
	                    		<a class="btn btn-sm btn-default" href="{{ route('formatos.verdetalles', $formato) }}">
	                    		    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Ver detalles
	                    		</a>
	                    	</td>
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
            $('#tablaListadoFormatos').DataTable({
                "paging":   true,
                "ordering": true,
                "info":     true,
                "pageLength": 10,
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