@extends('layouts.app')

@section('content')
	<h2>Detalles de la categoría {{ $categoria->nombre }}</h2>

	@if(Auth::user()->can('categorias.editar') || Auth::user()->can('categorias.borrar') || Auth::user()->can('categorias.listar'))
		<div class="row" style="margin-bottom: 20px">
			@can('categorias.listar')
                <div class="pull-left">
                    <a href="{{ route('categorias.listar') }}" class="btn btn-lg btn-default">
                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado categorías
                    </a>
                </div>
            @endcan
			<div class="pull-right">
				@can('categorias.editar')
					<a href="{{ route('categorias.editar', $categoria) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				@endcan
				@can('categorias.borrar')
					<button href="" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#modalBorrarCategoria">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
					</button>
				@endcan
			</div>
		</div>
	@endif

	<div class="row">
		<div class="form-horizontal">
			<div class="col-md6 col-sm-6 col-xs-12">
				
            <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
            <div class="col-md-10">
                <input id="inputNombre" type="text" class="form-control" name="nombre"value="{{ $categoria->nombre }}" readonly>
            </div>
		</div>
	</div>

	@can('categorias.borrar')
        {{-- Modal para confirmar el borrado de una direccion --}}
        <div class="modal fade" id="modalBorrarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalBorrarCategoria">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">¿Seguro que quiere eliminar esta categoría?</h3>
                    </div>
                    {{-- <div class="modal-body">
                        <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                        <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No, no borrar
                        </button>
                        <button type="button" onclick="$('#formularioBorrarCategoria').submit()" class="btn btn-danger">
                            Sí, confirmar el borrado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" id="formularioBorrarCategoria" action="{{ route('categorias.borrar', $categoria) }}">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection