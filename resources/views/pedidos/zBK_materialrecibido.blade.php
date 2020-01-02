@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-6 col-md-6">
			<h2>Recepción de material del pedido {{ $pedido->id }}</h2>
            <a href="{{ URL::previous() }}">
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Atrás</button>
            </a>
		</div>
		<div class="col-sm-6 col-md-6" style="text-align: right;">
			<h2>Estado del pedido: <span style="color: {{ $pedido->estadoPedido->color }}">{{ $pedido->estadoPedido->nombre_mostrar }}</span></h2>
            <div class="pull-right">
                <a class="btn btn-default" href="{{ route('pedidos.verhistorico', $pedido) }}">
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Histórico
                </a>
            </div>
		</div>
	</div>

	<div class="row col-md-12 col-sm-12">
        <div class="row col-md-12 col-sm-12">
        	<div class="col-md-6 col-sm-6">
        		<h3>Pedido para campo: <small>{{ $pedido->campo->nombre }}</small></h3>
        	</div>
        	<div class="col-md-6 col-sm-6">
        		<h3>Solictida el pedido: <small>{{ $pedido->usuarioRealizaPedido->nombre }}</small></h3>
        	</div>
        </div>
        <div class="row col-md-12 col-sm-12">
        	<div class="col-md-6 col-sm-6">
        		<h3>Proveedor: <small>{{ $pedido->proveedor->nombre }}</small></h3>
        	</div>
        	<div class="col-md-6 col-sm-6">
        		<h3>Fecha creación: <small>{{ $pedido->created_at }}</small></h3>
        	</div>
        </div>
    </div>

    <div class="row">
    	<button class="pull-right btn btn-lg btn-primary" onclick="MarcarTodoComoRecibido()">Marcar todo como recibido</button>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12 table-responsive">
        	<form method="POST" action="{{ route('pedidos.actualizarmaterialrecibido', $pedido) }}">
        		@csrf
        		@method('PUT')

	            <table id="tablaListadoLineasPedido" class="table table-condensed table-striped" style="width: 100%">
	                <thead>
	                    <tr>
	                    	<th>Línea</th>
	                    	<th>Categoría</th>
	                    	<th>Descripción</th>
	                    	<th>Unidades</th>
	                		<th>Formato</th>
	            			<th>Estado línea</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($pedido->lineasPedido as $linea)
	                        <tr>
	                        	<td>{{ $linea->numero_linea }}</td>
	                        	<td>{{ $linea->Categoria->nombre }}</td>
	                        	<td>{{ $linea->descripcion }}</td>
	                        	<td>{{ $linea->unidades }}</td>
	                        	<td>{{ $linea->Formato->nombre }}</td>
	                        	<td>
	                        		<select class="form-control selectEstadoPedido" name="selectEstadoLinea{{ $linea->id }}">
	                        			@foreach ($estadosLineas as $estadoLinea)
	                        				<option value="{{ $estadoLinea->id }}" {{ $linea->estado_linea_id == $estadoLinea->id ? "selected" : "" }}>{{ $estadoLinea->nombre }}</option>
	                        			@endforeach
	                        		</select>
	                        	</td>
	                        	{{-- <input type="hidden" name="lineaPedidoId{{ $linea->numero_linea }}" value="{{ $linea->numero_linea }}"> --}}
	                        </tr>
	                    @endforeach
	                </tbody>
	            </table>
	            <div class="row col-md-12 col-sm-12 col-xs-12">
	            	<div class="col-md-12 col-sm-12 col-xs-12">
	            		<button type="submit" class="bnt btn-lg btn-success pull-right">Actualizar estado recepción</button>	
	            	</div>
	            </div>
        	</form>
        </div>
    </div>
@endsection

@push('footscripts')
	<script type="text/javascript">
		//	Marca todas las líneas como recibidas
		function MarcarTodoComoRecibido()
		{
			var selectsEstadoLinea = document.getElementsByClassName("selectEstadoPedido");
			// console.log(selectsEstadoLinea);
			for(let item of selectsEstadoLinea)
			{
				var value = 1;
				for(let opcion of item.options)
				{
					// console.log(opcion.text + " - " + opcion.value);
					if(opcion.text == "recibida")
					{
						value = opcion.value;
						break;
					}
				}
				item.value = value;
			}
		}
	</script>
@endpush