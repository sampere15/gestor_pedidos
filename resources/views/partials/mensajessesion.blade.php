@if(Session::has('exito'))
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-success" role="alert">{!! Session::get('exito') !!}</div>
        </div>
    </div>
@endif

@if(Session::has('aviso'))
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-warning" role="alert">{!! Session::get('aviso') !!}</div>
        </div>
    </div>
@endif

@if(Session::has('error'))
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-danger" role="alert">{!! Session::get('error') !!}</div>
        </div>
    </div>
@endif