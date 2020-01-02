<h2>Test</h2>

@can('pedidos.crear')
	<h4>Pedidos</h4>
@endcan

@can('usuarios.crear')
	<h4>Usuarios</h4>
@endcan

@canatleast(['usuarios.crear', 'pedidos.crear'])
	<h4>Alguno de los dos</h4>
@endcanatleast