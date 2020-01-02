<!DOCTYPE html>
<html>
    <head>
	</head>
	<body>
		@if (isset($datos['titulo']))
			<h3>{{ $datos['titulo'] }}</h3>
		@endif

		@if (isset($datos['cuerpo']))
			<p>{{ $datos['cuerpo'] }}</p>
		@endif
	</body>
</html>