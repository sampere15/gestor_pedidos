<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GNK Pedidos') }}</title>

        @stack('csstopbefore')

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}"> --}}

        {{-- CSS para obtener el lateral del dashboard --}}
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

        {{-- Nuestro propio CSS --}}
        <link rel="stylesheet" href="{{ asset('css/mystile.css') }}">

        {{-- CSS propio que pueda tener cierta vista específica --}}
        @stack('csstopafter')

        {{-- Por si fuese necesario incluir java script en el head desde alguna vista específica --}}
        @stack('headerscripts')
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 col-md-2 sidebar">
                    <ul>
                        <h3><a href="{{ url('/home') }}" style="text-decoration: none">GNK</a></h3>
                    </ul>

                    @if(Auth::user())
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px; margin-left: 0px">
                            <li>Usuario: {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</li>
                        </ul>
                    @endif

                    {{-- @canatleast(['pedidos.crear', 'pedidos.listarvalidados', 'pedidos.listarcursados']) --}}
                        {{-- @can('pedidos.crear') --}}
                    @if(Auth::user() && (Auth::user()->can('pedidos.crear') || Auth::user()->can('pedidos.listarsolicitados') 
                        || Auth::user()->can('pedidos.listarvalidados') || Auth::user()->can('pedidos.listarcursados')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonPedidos" data-parent="#accordion" href="#collapsePedidos" aria-expanded="true" aria-controls="collapsePedidos" style="padding: 0px 0px 0px 20px">
                                Pedidos +
                                </a>
                            </li>
                            <div id="collapsePedidos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('pedidos.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.crear') }}">Nuevo pedido</a></li>
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.mispedidosguardados') }}">Mis pedidos guardados</a></li>
                                        <li style="margin-left: 20px"><a href="{{ route('usuarios.mispedidos')}}">Mis pedidos</a></li>
                                    @endcan
                                    @can('pedidos.listarsolicitados')
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.listarsolicitados') }}">Pedidos solicitados</a></li>
                                    @endcan
                                    @can('pedidos.listarvalidados')
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.listarvalidados') }}">Pedidos validados</a></li>
                                    @endcan
                                    @can('pedidos.comunicaraproveedor')
                                        {{-- <li style="margin-left: 20px"><a href="{{ route('pedidos.listarcursados') }}">Pedidos cursados</a></li> --}}
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.listarpendientescomunicar') }}">Pendiente de comunicar al proveedor</a></li>
                                    @endcan
                                    @can('pedidos.listarpendientes')
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.listarpendientes') }}">Pedidos pendientes recibir</a></li>
                                    @endcan
                                    @can('pedidos.listarfinalizados')
                                        <li style="margin-left: 20px"><a href="{{ route('pedidos.listarfinalizados') }}">Pedidos finalizados</a></li>
                                    @endcan
                                    @can('pedidos.listartodos')
                                        @if(Auth::user()->isRole('administrador'))
                                            <li style="margin-left: 20px"><a href="{{ route('pedidos.listartodos') }}">TODOS LOS PEDIDOS</a></li>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </ul>
                    @endif
                        {{-- @endcan --}}
                    {{-- @endcanatleast --}}

                    {{-- @canatleast(['usuarios.listar', 'usuarios.crear']) --}}
                    {{-- @can('usuarios.listar') --}}
                    @if(Auth::user() && (Auth::user()->can('usuarios.listar') || Auth::user()->can('usuarios.crear')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonUsuarios" data-parent="#accordion" href="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUsuarios" style="padding: 0px 0px 0px 20px">
                                Usuarios +
                                </a>
                            </li>
                            <div id="collapseUsuarios" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('usuarios.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('usuarios.crear') }}">Nuevo usuario</a></li>
                                    @endcan
                                    @can('usuarios.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('usuarios.listar') }}">Lista usuarios</a></li>
                                    @endcan
                                </div>
                            </div>
                        </ul>
                       {{--  <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            @can('usuarios.listar')
                                <li><a href="{{ route('usuarios.listar') }}">Usuarios</a></li>
                            @endcan
                            @can('usuarios.crear')
                                <li><a href="{{ route('usuarios.crear') }}">Nuevo usuario</a></li>
                            @endcan
                        </ul> --}}
                    @endif
                    {{-- @endcan --}}
                    {{-- @endcanatleast --}}

                    @if(Auth::user() && (Auth::user()->can('campos.crear') || Auth::user()->can('campos.listar')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonCampos" data-parent="#accordion" href="#collapseCampos" aria-expanded="true" aria-controls="collapseCampos" style="padding: 0px 0px 0px 20px">
                                Campos +
                                </a>
                            </li>
                            <div id="collapseCampos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('campos.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('campos.crear') }}">Nuevo campo</a></li>
                                    @endcan
                                    @can('campos.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('campos.listar') }}">Listar campos</a></li>
                                    @endcan
                                </div>
                            </div>
                        </ul>
                    @endif

                    @if(Auth::user() && (Auth::user()->can('departamentos.crear') || Auth::user()->can('departamentos.listar')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonDepartamentos" data-parent="#accordion" href="#collapseDepartamentos" aria-expanded="true" aria-controls="collapseDepartamentos" style="padding: 0px 0px 0px 20px">
                                Departamentos +
                                </a>
                            </li>
                            <div id="collapseDepartamentos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('departamentos.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('departamentos.crear') }}">Nuevo departamento</a></li>
                                    @endcan
                                    @can('departamentos.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('departamentos.listar') }}">Listar departamentos</a></li>
                                    @endcan
                                </div>
                            </div>
                        </ul>
                    @endif

                    @if(Auth::user() && (Auth::user()->can('proveedores.listar') || Auth::user()->can('proveedores.crear')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonProveedores" data-parent="#accordion" href="#collapseProveedores" aria-expanded="true" aria-controls="collapseProveedores" style="padding: 0px 0px 0px 20px">
                                Proveedores +
                                </a>
                            </li>
                            <div id="collapseProveedores" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('proveedores.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('proveedores.crear') }}">Crear proveedor</a></li>
                                    @endcan
                                    @can('proveedores.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('proveedores.listar') }}">Listar proveedores</a></li>
                                    @endcan
                                </div>
                            </div>
                        </ul>
                    @endif

                    @if(Auth::user() && (Auth::user()->can('categorias.listar') || Auth::user()->can('categorias.crear') 
                        || Auth::user()->can('formatos.listar') || Auth::user()->can('formatos.crear') 
                        || Auth::user()->can('sociedades.listar') || Auth::user()->can('sociedades.crear')))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonOtros" data-parent="#accordion" href="#collapseOtros" aria-expanded="true" aria-controls="collapseOtros" style="padding: 0px 0px 0px 20px">
                                Otros +
                                </a>
                            </li>
                            <div id="collapseOtros" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('categorias.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('categorias.crear') }}">Crear categoría</a></li>
                                    @endcan
                                    @can('categorias.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('categorias.listar') }}">Listar categorías</a></li>
                                    @endcan
                                    @can('formatos.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('formatos.crear') }}">Crear formatos</a></li>
                                    @endcan
                                    @can('formatos.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('formatos.listar') }}">Listar formatos</a></li>
                                    @endcan
                                    @can('sociedades.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('sociedades.crear') }}">Crear sociedad</a></li>
                                    @endcan
                                    @can('sociedades.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('sociedades.listar') }}">Listar sociedades</a></li>
                                    @endcan
                                    {{-- @can('departamentos.crear')
                                        <li style="margin-left: 20px"><a href="{{ route('departamentos.crear') }}">Crear departamento</a></li>
                                    @endcan
                                    @can('departamentos.listar')
                                        <li style="margin-left: 20px"><a href="{{ route('departamentos.listar') }}">Listar departamentos</a></li>
                                    @endcan --}}
                                </div>
                            </div>
                        </ul>
                    @endif

                    @if(Auth::user() && (Auth::user()->can('informes.gastos') || Auth::user()->can('informes.porcategorias') || Auth::user()->can('informes.lineascategorias') ))
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a onclick="cambiarIcono(this)" role="button" data-toggle="collapse" id="acordeonInformes" data-parent="#accordion" href="#collapseInformes" aria-expanded="true" aria-controls="collapseInformes" style="padding: 0px 0px 0px 20px">
                                Informes +
                                </a>
                            </li>
                            <div id="collapseInformes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body" style="padding-top: 0px">
                                    @can('informes.gastos')
                                        <li style="margin-left: 20px"><a href="{{ route('informes.gastos') }}">Informe gastos</a></li>
                                    @endcan
                                    @can("informes.porcategorias")
                                        <li style="margin-left: 20px"><a href="{{ route('informes.porcategorias') }}">Informe por categoría</a></li>
                                    @endcan
                                    @can("informes.lineascategorias")
                                        <li style="margin-left: 20px"><a href="{{ route('informes.lineascategorias') }}">Informe líneas por categorías</a></li>
                                    @endcan
                                </div>
                            </div>
                        </ul>
                    @endif

                    @if(Auth::user())
                        <hr>
                        <ul class="nav nav-sidebar" style="margin-bottom: 10px">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Cerrar sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    @endif
                </div>

                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    {{-- Incluimos el trodo de codigo utilizado para mostrar los mensajes de sesion (alert, success, etc) --}}
                    @include('partials.mensajessesion')

                    {{-- Aqui es donde ira el basicamente el codigo del resto de las vistas --}}
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- jQuery -->
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
        <script src="{{ asset('js/jquery.min.js') }}"></script>

        <!-- Latest compiled and minified JavaScript -->
        {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}

        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <script type="text/javascript">
            //  Cuando pulsamos sobre el acordeon de los pedidos
            function cambiarIcono(element)
            {
                //  Si tiene el icono + lo cambiaremos por - y viceversa
                if(element.text.indexOf('+') > 0)
                    element.text = element.text.replace('+', '-');
                else if(element.text.indexOf('-') > 0)
                    element.text = element.text.replace('-', '+');
            }
        </script>

        @stack('footscripts')
    </body> 
</html>