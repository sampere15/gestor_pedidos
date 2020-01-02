@extends('layouts.app')

@section('content')

	<h2>Listado de categorías</h2>

	@can('categorias.crear')
		<div class="row" style="margin-bottom: 20px">
			<div class="pull-right">
				<a href="{{ route('categorias.crear') }}" class="btn btn-lg btn-success">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva categoría
				</a>
			</div>
		</div>
	@endcan

	<div class="row col-md-12 col-sm-12 col-xs-12">
		<table id="tablaListadoCategorias" class="table table-condensed table-striped"  style="width: 30%">
            <thead>
                <tr>
                    <th width="10px"></th>
                    <th>Nombre</th>
                    @can('categorias.editar')
                    	<th width="10px"></th>
                    @endcan
                </tr>
            </thead>
            <tbody>
        		@foreach ($categorias->where('activo') as $categoria)
        			<tr>
        				<td>{{ $categoria->id }}</td>
        				<td>{{ $categoria->nombre }}</td>
        				@can('categorias.editar')
	                    	<td>
	                    		<a class="btn btn-sm btn-default" href="{{ route('categorias.verdetalles', $categoria) }}">
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
            $('#tablaListadoCategorias').DataTable({
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