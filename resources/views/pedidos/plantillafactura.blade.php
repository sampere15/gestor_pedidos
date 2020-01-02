<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title>Orden de pedido {{ $pedido['id'] }}</title>
	</head>
	<body>
		<div>
			<table width="100%" style="border: 1px black solid; text-align: center">
				{{-- <tr>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
					<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				</tr> --}}
				<tr>
					<td colspan="3"  style="border: 1px black solid" align="center" height="50px">
						<img src="{{ asset('images/GNK_Golf.jpg') }}" height="100%">
					</td>
					<td colspan="3" style="text-align: center;"><h2>Orden de pedido</h2></td>
					<td colspan="6">
						<table width="100%" style="border: 1px black solid;">
							{{-- <tr>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
								<td width="{{ 100/12 }}%"></td>
							</tr> --}}	
							<tr>
								<td colspan="2" style="text-align: center"><b>Datos de entrega</b></td>
							</tr>
							<tr>
								<td colspan="2"><b>Dirección:</b> {{ $pedido['direccion']['calle'] }}</td>
							</tr>
							<tr>
								<td><b>Código Postal:</b> {{ $pedido['direccion']['codigo_postal'] }}</td>
								<td><b>Población:</b> {{ $pedido['direccion']['ciudad'] }}</td>
							</tr>
							<tr>
								<td><b>Provincia:</b> {{ $pedido['direccion']['provincia'] }}</td>
								<td><b>País:</b> {{ $pedido['direccion']['pais'] }}</td>
							</tr>
							<tr>
								<td colspan="2"><b>Peronsa contacto:</b> {{ $pedido['direccion']['persona_contacto'] }}</td>
							</tr>
							<tr>
								<td colspan="2"><b>Teléfono contacto:</b> {{ $pedido['direccion']['numero_contacto'] }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<hr style="text-decoration: none;border: 0px white">
		<table width="100%" style="border: 1px black solid;">
			{{-- <tr>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
			</tr> --}}
			<tr>
				<td colspan="4"><b>Proveedor</b></td>
				<td colspan="2"><b>Fecha</b></td>
				<td colspan="4"><b>Campo</b></td>
				<td colspan="2"><b>Número pedido</b></td>
			</tr>
			<tr>
				<td colspan="4">{{ $pedido['proveedor']['nombre'] }}</td>
				<td colspan="2">{{ $pedido['created_at'] }}</td>
				<td colspan="4">{{ $pedido['campo']['nombre'] }}</td>
				<td colspan="2">{{ $pedido['id'	] }}</td>
			</tr>
		</table>
		<hr style="text-decoration: none;border: 0px white">
		<table <table width="100%">
			<tr>
				<td>{{ $pedido['solicitado_al_proveedor'] }}</td>
				<td>{{ $pedido['ya_recibido'] }}</td>
			</tr>
			<tr>
				<td colspan="2"><b>Líneas de pedido</b></td>
				@if($pedido['solicitado_al_proveedor'] == 1)
					<td colspan="10" style="color: red"><b>PEDIDO YA ENVIADO POR PARTE DEL PROVEEDOR. ESTO ES SÓLO LA COMUNICACIÓN DEL PEDIDO</b></td>
				@elseif($pedido['ya_recibido'] == 1)
					<td colspan="10" style="color: red"><b>PEDIDO YA RECIBIDO. ESTO ES SÓLO LA COMUNICACIÓN DEL PEDIDO</b></td>
				@endif
			</tr>
		</table>
		<table width="100%" style="border: 1px black solid;">
			{{-- <tr>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
				<td width="{{ 100/12 }}%" style="border: 1px red solid;">.</td>
			</tr> --}}
			<tr>
				<td><b>Nº Línea</b></td>
				<td colspan="2"><b>Categoría</b></td>
				<td colspan="3"><b>Descipción</b></td>
				<td><b>Cantidad</b></td>
				<td colspan="2"><b>Formato</b></td>
				<td><b>Precio</b></td>
				<td colspan="2"><b>Total</b></td>
			</tr>
			@foreach ($lineas as $linea)
				<tr>
					<td>{{ $linea['numero_linea'] }}</td>
					<td colspan="2">{{ $linea['categoria']['nombre'] }}</td>
					<td colspan="3">{{ $linea['descripcion'] }}</td>
					<td>{{ $linea['unidades'] }}</td>
					<td colspan="2">{{ $linea['formato']['nombre'] }}</td>
					<td>{{ $linea['precio'] }}</td>
					<td colspan="2">{{ $linea['unidades'] * $linea['precio'] }} €</td>
				</tr>
			@endforeach
		</table>
		<hr>
		<div>
			<table width="100%">
				<tr>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
					<td width="{{ 100/12 }}%"></td>
				</tr>
				<tr>
					<td colspan="9"></td>
					<td colspan="3"><b>Total pedido: {{ $pedido['total_pedido'] }} €</b></td>
				</tr>
			</table>
		</div>
	</body>
</html>