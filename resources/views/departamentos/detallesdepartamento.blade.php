@extends('layouts.app')

@section('content')
	<h2>Detalles del departamento {{ $departamento->nombre }}</h2>

	@if(Auth::user()->can('departamentos.editar') || Auth::user()->can('departamentos.borrar') || Auth::user()->can('departamentos.listar'))
		<div class="row" id="1" style="margin-bottom: 20px">
			@can('departamentos.listar')
                <div class="pull-left">
                    <a href="{{ route('departamentos.listar') }}" class="btn btn-lg btn-default">
                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado departamentos
                    </a>
                </div>
            @endcan
			<div class="pull-right">
				@can('departamentos.editar')
					<a href="{{ route('departamentos.editar', $departamento) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				@endcan
				@can('departamentos.borrar')
					<button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#modalBorrarDepartamento">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
					</button>
				@endcan
			</div>
		</div>
	@endif

	<div class="row" id="2">
		<div class="form-horizontal">
			<div class="col-md6 col-sm-6 col-xs-12">
                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
                <div class="col-md-10">
                    <input id="inputNombre" type="text" class="form-control" value="{{ $departamento->nombre }}" readonly>
                </div>
            </div>
		</div>
	</div>

    <hr>

    {{-- Campos en los que está presente este departamento --}}
    <div class="row" id="3">
        @if($departamento->activo)
            <div class="col-md-3 col-sm-3 col-xs-12">
                <h3>Campos</h3>
                <div class="row">
                    <div class="pull-right" style="margin-right: 40px">
                        @can('campos.editarcampos')
                            <a href="{{ route('departamentos.editarcampos', $departamento) }}" class="btn btn-sm btn-warning">Editar</a>
                        @endcan
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                        @foreach ($departamento->campos as $campo)
                            <tr>
                                <td>
                                    <a href="{{ route('campos.verdetalles', $campo) }}">{{ $campo->nombre }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
    </div>

    <hr>

    {{-- Los usuarios que tienen diferentes permisos sobre este departamento --}}
    <div class="row">
        <h3>Usuarios con permiso sobre este departamento</h3>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <h4>Puede crear pedidos</h4>
            @if (count($usuarios_crear_pedidos) > 0)
                <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                    @foreach ($usuarios_crear_pedidos as $usuario)
                        <tr>
                            <td>
                                <a href="{{ route('usuarios.verdetalles', $usuario->id) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }} (</a>
                                @foreach ($usuario->camposConPermisoSegunDepartamento($departamento->id) as $campo)
                                    <a href="{{ route('campos.verdetalles', $campo->id) }}" title="{{ $campo->nombre }}">{{ $campo->abreviatura }}</a>
                                @endforeach
                                )
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                No hay usuarios que tengan este permiso asignado
            @endif
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <h4>Puede validar pedidos</h4>
            @if (count($usuarios_validar_pedidos) > 0)
                <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                    @foreach ($usuarios_validar_pedidos as $usuario)
                        <tr>
                            <td>
                                <a href="{{ route('usuarios.verdetalles', $usuario->id) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }} (</a>
                                @foreach ($usuario->camposConPermisoSegunDepartamento($departamento->id) as $campo)
                                    <a href="{{ route('campos.verdetalles', $campo->id) }}" title="{{ $campo->nombre }}">{{ $campo->abreviatura }}</a>
                                @endforeach
                                )
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                No hay usuarios que tengan este permiso asignado
            @endif
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <h4>Puede cursar pedidos</h4>
            @if (count($usuarios_cursar_pedidos) > 0)
                <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                    @foreach ($usuarios_cursar_pedidos as $usuario)
                        <tr>
                            <td>
                                <a href="{{ route('usuarios.verdetalles', $usuario->id) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }} (</a>
                                @foreach ($usuario->camposConPermisoSegunDepartamento($departamento->id) as $campo)
                                    <a href="{{ route('campos.verdetalles', $campo->id) }}" title="{{ $campo->nombre }}">{{ $campo->abreviatura }}</a>
                                @endforeach
                                )
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                No hay usuarios que tengan este permiso asignado
            @endif
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <h4>Puede comunicar al proveedor</h4>
            @if (count($usuarios_comunicaraproveedor_pedidos) > 0)
                <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                    @foreach ($usuarios_comunicaraproveedor_pedidos as $usuario)
                        <tr>
                            <td>
                                <a href="{{ route('usuarios.verdetalles', $usuario->id) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }} (</a>
                                @foreach ($usuario->camposConPermisoSegunDepartamento($departamento->id) as $campo)
                                    <a href="{{ route('campos.verdetalles', $campo->id) }}" title="{{ $campo->nombre }}">{{ $campo->abreviatura }}</a>
                                @endforeach
                                )
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                No hay usuarios que tengan este permiso asignado
            @endif
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <h4>Puede indicar material como recepcionado</h4>
            @if (count($usuarios_recepcionar_pedidos) > 0)
                <table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
                    @foreach ($usuarios_recepcionar_pedidos as $usuario)
                        <tr>
                            <td>
                                <a href="{{ route('usuarios.verdetalles', $usuario->id) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }} (</a>
                                @foreach ($usuario->camposConPermisoSegunDepartamento($departamento->id) as $campo)
                                    <a href="{{ route('campos.verdetalles', $campo->id) }}" title="{{ $campo->nombre }}">{{ $campo->abreviatura }}</a>
                                @endforeach
                                )
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                No hay usuarios que tengan este permiso asignado
            @endif
        </div>
    </div>

	@can('departamentos.borrar')
        {{-- Modal para confirmar el borrado de una direccion --}}
        <div class="modal fade" id="modalBorrarDepartamento" tabindex="-1" role="dialog" aria-labelledby="modalBorrarDepartamento">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">¿Seguro que quiere eliminar este departamento?</h3>
                    </div>
                    {{-- <div class="modal-body">
                        <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                        <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No, no borrar
                        </button>
                        <button type="button" onclick="$('#formularioBorrarDepartamento').submit()" class="btn btn-danger">
                            Sí, confirmar el borrado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" id="formularioBorrarDepartamento" action="{{ route('departamentos.borrar', $departamento) }}">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection