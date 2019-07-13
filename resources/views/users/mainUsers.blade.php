@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row block-search">
            <div class="col-sm-2 col-md-2">
                <h5 class="title-main">Usuarios</h5>
            </div>
            <div class="col-sm-2 col-md-6"></div>
            <div class="col-sm-6 col-md-4">
                <form method="get" action="{{route('user.list')}}" id="searchUsers"  >
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
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Publicaciones</th>
                    <th class="text-center">Comentarios</th>
                    <th class="text-center">Ver Perfil</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $user)
                    <tr>
                        <td>
                            <img class="photo-profile-user" src="{{ asset('images/'.$user->name.'_'.$user->id.'/'.$user->profile_photo) }}" />
                            &nbsp;
                            <span>Nombre: {{ $user->name.' '.$user->surname }}</span>
                            <br>&nbsp;
                            <span>Correo: {{ $user->email }}</span>
                            <br>&nbsp;
                            <span>Teléfono: {{ $user->phone }}</span>
                        </td>
                        <td class="text-center">
                            @if ($user->deleted_at==NULL)
                                <img class="status" src="{{asset('images/activo.png')}}" data-id="{{ $user->id }}" data-toggle="modal" data-target="#desactivateUser">
                            @else
                                <img  class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $user->id }}" data-toggle="modal" data-target="#activateUser">
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($user->publications_count>0)
                                <a id="link-publications" href="{{route('user.publications',['id'=>$user->id])}}" class="btn btn-dark">{{ $user->publications_count }}</a>
                            @else
                                <a id="link-publications" href="#" class="btn btn-dark">{{ $user->publications_count }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($user->comments_count>0)
                                <a id="link-comments" href="{{route('user.comments',['id'=>$user->id])}}" class="btn btn-primary">{{ $user->comments_count }}</a>
                            @else
                                <a id="link-comments" href="#" class="btn btn-dark">{{ $user->comments_count }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            <a id="link-profile" href="{{route('user.profile',['id'=>$user->id])}}" class="btn btn-warning"><i class="fas fa-user-edit fa-1x"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="clearfix"></div>
            {{ $data->links() }}
        </div>
    </div>
    <div class="modal fade" id="activateUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Gestión de Usuarios</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('user.activate','test') }}" method="POST" accept-charset="utf-8">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>¿Esta seguro de activar éste usuario?</h4>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn boton" class="activate">Activar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="desactivateUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Gestión de Usuarios</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('user.desactivate','test') }}" method="POST" accept-charset="utf-8">
                    {{method_field('delete')}}
                    {{csrf_field()}}
                    <div class="modal-body">                    
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input type="hidden" id="route" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>¿Esta seguro de desactivar éste usuario?</h4>
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
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#activateUser').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #user_id').val(id);
            });

            $('#desactivateUser').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #user_id').val(id);
            }); 
        });
    </script>
@endsection