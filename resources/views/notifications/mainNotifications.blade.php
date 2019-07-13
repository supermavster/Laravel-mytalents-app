@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row block-search">
        <div class="col-sm-2 col-md-2">
            <h5 class="title-main">Notificaciones</h5>
        </div>
        <div class="col-sm-2 col-md-4"></div>
        <div class="col-sm-2 col-md-2">
            <a href="{{ route('notification.create') }}" class="btn boton">Crear Nueva</a>
        </div>
        <div class="col-sm-6 col-md-4">
            <form method="get" action="{{route('notification.list')}}" id="searchUsers"  >
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-md-10">                                
                            <input type="search" name="search" id="search" autocomplete="search" autofocus class="form-control" placeholder="Buscar...">                                
                        </div>                            
                        <button type="submit" class="btn search"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <table class="table">
            <thead class="table-title">
            <tr>
                <th class="text-center">Detalle Notificación</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Eliminar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $notify)
                <tr>
                    <td class="text-center">
                        {{ $notify->detail }}
                    </td>
                    <td class="text-center">
                        @if ($notify->deleted_at==NULL)
                             <img  class="status" src="{{asset('images/activo.png')}}" data-id="{{ $notify->id }}" data-toggle="modal" data-target="#desactivateNotification">
                        @else
                            <img  class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $notify->id }}" data-toggle="modal" data-target="#activateNotification">
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-warning" data-id="{{ $notify->id }}" data-toggle="modal"  data-target="#destroyNotification"><i class="fas fa-trash-alt"></i></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
    <div class="modal fade" id="desactivateNotification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Control de Notificaciones</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('notification.desactivate','test') }}" method="POST" accept-charset="utf-8">
                {{method_field('delete')}}
                {{csrf_field()}}
                <div class="modal-body">                    
                    <input type="hidden" name="notification_id" id="notification_id" value="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="modal-title" id="myModalLabel">¿Esta seguro de desactivar ésta notificacion?</h5>
                            </div>
                        </div>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" name="" class="btn boton" value="Desactivar">        
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="activateNotification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Control de Notificaciones</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('notification.activate','test') }}" method="POST" accept-charset="utf-8">
                {{csrf_field()}}
                <div class="modal-body">                    
                    <input type="hidden" name="notification_id" id="notification_id" value="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="modal-title" id="myModalLabel">¿Esta seguro de activar ésta notificacion?</h5>
                            </div>
                        </div>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn boton" value="Activar">        
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="destroyNotification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Control de Notificaciones</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('notification.destroy','test') }}" method="POST" accept-charset="utf-8">
                {{method_field('delete')}}
                {{csrf_field()}}
                <div class="modal-body">                    
                    <input type="hidden" name="notification_id" id="notification_id" value="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="modal-title" id="myModalLabel">¿Esta seguro de eliminar permanentemente ésta notificacion?</h5>
                            </div>
                        </div>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn boton" value="Eliminar">        
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#activateNotification').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #notification_id').val(id);
            });

            $('#desactivateNotification').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #notification_id').val(id);
            });
            $('#destroyNotification').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #notification_id').val(id);
            });             
        });
    </script>
@endsection