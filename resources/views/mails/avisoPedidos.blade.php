<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h3>{{ $datos['titulo'] }}</h3>

        @if(isset($datos['url']))
            @if(isset($datos['textourl']))
                <p>{{ $datos['textourl'] }}</p>
            @endif
            <a href="{{ $datos['url'] }}">Acceder</a>
            <hr>
            Si no puede puede pinchar en el enlace pruebe a copiar la url directamente en su navegador: {{ $datos['url'] }}
        @endif
    </body>
</html>