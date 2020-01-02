<!DOCTYPE html>
<html>
    <head>
        <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
    </head>
    <body>
        <h3>{{ $datos['titulo'] }}</h3>

        <p>{{ $datos['cuerpo'] }}</p>

        @if (isset($datos['pedido']->motivo_cancelacion) && $datos['pedido']->motivo_cancelacion != null)
            <p style="color: #9e2626">El motivo de cancelación ha sido: {{ $datos['pedido']->motivo_cancelacion }}</p>
        @endif

        @if (isset($datos['pedido']))
            <table style="width: 80%">
                <thead >
                    <tr>
                        <th>Línea</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Unidades</th>
                        <th>Formato</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos['pedido']->lineasPedido as $linea)
                        <tr>
                            <td>{{ $linea->numero_linea }}</td>
                            <td>{{ $linea->Categoria->nombre }}</td>
                            <td>{{ $linea->descripcion }}</td>
                            <td>{{ $linea->unidades }}</td>
                            <td>{{ $linea->Formato->nombre }}</td>
                            <td>{{ $linea->precio }} €</td>
                            <td>{{ $linea->precio * $linea->unidades }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Pulsando en el siguiente enlace podrá ver toda la información sobre el pedido: <a href="{{ $datos['url_pedido'] }}">Pulse aquí para más información</a></p>
        @endif
    </body>
</html>