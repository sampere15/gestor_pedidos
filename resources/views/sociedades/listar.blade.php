@extends('layouts.app')

@section('content')

	<h2>Listado de sociedades</h2>

	@can('sociedades.crear')
		<div class="row" style="margin-bottom: 20px">
			<div class="pull-right">
				<a href="{{ route('sociedades.crear') }}" class="btn btn-lg btn-success">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva sociedad
				</a>
			</div>
		</div>
	@endcan

	<div class="row col-md-12 col-sm-12 col-xs-12">
		<table id="tablaListadoSociedades" class="table table-condensed table-striped"  style="width: 100%">
            <thead>
                <tr>
                    <th width="10px"></th>
                    <th>Nombre</th>
                    <th>CIF</th>
                    @can('sociedades.verdetalles')
                        <th width="10px"></th>
                    @endcan
                </tr>
            </thead>
            <tbody>
        		@foreach ($sociedades as $sociedad)
        			<tr>
        				<td>{{ $sociedad->id }}</td>
        				<td>{{ $sociedad->nombre }}</td>
        				<td>{{ $sociedad->cif }}</td>
                        @can('sociedades.verdetalles')
                            <td>
                                <a class="btn btn-sm btn-default" href="{{ route('sociedades.verdetalles', $sociedad) }}">
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
            $('#tablaListadoSociedades').DataTable({
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