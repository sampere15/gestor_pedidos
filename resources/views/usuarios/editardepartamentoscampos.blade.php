@extends('layouts.app')

@section('content')
    <h2>Editar Departamentos-Campos del usuario <a href="{{ route('usuarios.verdetalles', $usuario) }}">{{ $usuario->nombre }} {{ $usuario->apellidos }}</a></h2>

    <div class="row">
        <div class="pull-left">
            <a href="{{ route('usuarios.verdetalles', $usuario) }}" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver a los detalles del usuario sin guardar los cambios
            </a>
        </div>
    </div>

    <form action="{{ route('usuarios.actualizardepartamentoscampos', $usuario) }}" method="POST">
        @csrf

        <div class="row">
            @foreach ($departamentos as $departamento)
                <hr style="border-style: inset; border-width: 1px;border-color: #39bfc9">
                <h4 style="color: #39bfc9">Campos con permiso para el departamento {{ $departamento->nombre }}</h4>
                <div class="row">
                    @foreach ($departamentosCampos as $departamentoCampos)
                        @if($departamentoCampos->departamento_id == $departamento->id)
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="checkbox" name="permisos[]" value="{{ $departamentoCampos->id }}" {{ $usuario->comprobarPermisoDepartamentoCampo($departamentoCampos->id) ? "checked" : "" }}> {{ $departamentoCampos->campo->nombre }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            <hr style="border-style: inset; border-width: 1px;border-color: #39bfc9">
        </div>

        <button class="btn btn-success">Guardar Cambios</button>

    </form>
@endsection