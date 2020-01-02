@extends('layouts.app')

@section('content')
	<h2>Histórico pedido {{ $pedido_id }}</h2>

	<a href="{{ URL::previous() }}">
		<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Atrás</button>
	</a>

	<div class="row">
		<table class="table table-condensed table-striped" style="width:60%">
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Estado</th>
					<th>Usuario</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($historicoEstados as $historico)
					<tr>
						<td>{{ $historico->fecha }}</td>
						<td>{{ $historico->estado }}</td>
						<td>{{ $historico->usuario->nombre }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection