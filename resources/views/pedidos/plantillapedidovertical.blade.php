<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title>Orden de pedido {{ $pedido['id'] }}</title>
	</head>
	<body>
		<div style="text-align: center">
			<h2>Orden de pedido</h2>			
		</div>
		<div>
			<table width="100%" style="border: 1px black solid;">
				<tr>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="3"  style="border: 1px black solid" align="center" height="100px">
						<img src="{{ asset('images/GNK_Golf.jpg') }}" height="100%">
					</td>
					<td colspan="1" style="border: 1px black solid"></td>
					<td colspan="6" style="border: 1px black solid;">
						<table width="100%" style="border: 1px red solid;">
							<tr>
								<td colspan="2" style="text-align: center"><h3>Datos de entrega</h3></td>
							</tr>
							<tr>
								<td colspan="2"><b>Dirección:</b> Calle Ceiba S/N, Planta 1 Iz, Town Center</td>
							</tr>
							<tr>
								<td><b>Código Postal:</b> 30700</td>
								<td><b>Población:</b> Torre Pacheco</td>
							</tr>
							<tr>
								<td><b>Provincia:</b> Murcia</td>
								<td><b>País:</b> España</td>
							</tr>
							<tr>
								<td colspan="2"><b>Peronsa contacto:</b> Belén Mosquera</td>
							</tr>
							<tr>
								<td colspan="2"><b>Teléfono contacto:</b> 647681924</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<hr style="text-decoration: none;border: 0px white">
		<div>
			<table width="100%" style="border: 1px black solid;">
				<tr>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="3"><b>Proveedor</b></td>
					<td colspan="2"><b>Fecha</b></td>
					<td colspan="3"><b>Campo</b></td>
					<td colspan="2"><b>Número pedido</b></td>
				</tr>
				<tr>
					<td colspan="3">Nombre del proveedor</td>
					<td colspan="2">25-12-2018</td>
					<td colspan="3">Hacienda Riquelme</td>
					<td colspan="2">99999</td>
				</tr>
			</table>
		</div>
		<hr style="text-decoration: none;border: 0px white">
		<div>
			<h2 style="color: red;"><u>PEDIDO YA ENVIADO POR PARTE DEL PROVEEDOR. ESTO ES SÓLO LA COMUNICACIÓN DEL PEDIDO</u></h2>
		</div>
		<div>
			<h2 style="color: red;"><u>PEDIDO YA RECIBIDO. ESTO ES SÓLO LA COMUNICACIÓN DEL PEDIDO</u></h2>
		</div>
		<hr style="text-decoration: none;border: 0px white">
		<h4>Líneas de pedido</h4>
		<div>
			<table width="100%" style="border: 1px black solid;">
				<tr>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td><b>Línea</b></td>
					<td colspan="2"><b>Categoría</b></td>
					<td colspan="3"><b>Descripción</b></td>
					<td><b>Unidades</b></td>
					<td><b>Formato</b></td>
					<td><b>Precio</b></td>
					<td><b>Total</b></td>
				</tr>
				@for ($i = 0; $i < 12; $i++)
					<tr>
						<td>{{ $i + 1 }}</td>
						<td colspan="2">Categoría</td>
						<td colspan="3">Descripción</td>
						<td>65</td>
						<td>toneladas</td>
						<td>8</td>
						<td>1928 €</td>
					</tr>
				@endfor
			</table>
		</div>
		<hr>
		<div>
			<table>
				<tr>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
				</tr>
				<tr>
					<td colspan="7"></td>
					<td colspan="3"><b>Total pedido: </b>4576 €</td>
				</tr>
			</table>
		</div>
	</body>
</html>