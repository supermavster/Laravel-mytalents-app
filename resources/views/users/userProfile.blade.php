@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="border:3px solid #E18B00;border-radius: 9px">
            <br>
            <div class="row" ><!--photo profile, name-->
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center">
                    @if ($data->profile_photo)
                        <img style="width: 110px;height:100px;border:4px solid #E18B00;border-radius: 5px;" src="{{ asset('images/'.$data->name.'_'.$data->id.'/'.$data->profile_photo) }}" />
                    @endif
                    <br>
                    <br>
                    <strong>{{$data->name.' '.$data->surname}}</strong>
                    <br>
                    <strong>{{$data->email}}</strong>
                </div>
                <div class="col-md-4"></div>
            </div>
            <hr>
            <div class="row"><!--Seguidores,seguidos,likes-->
                <div class="col-md-4 text-right">
                    Seguidores:&nbsp;
                    <a href="" id="link-publications" class="btn btn-primary" title="Lista de Seguidores" data-id="" data-toggle="modal" data-target="#listaSeguidores">{{ $seguidores}}</a>
                </div>
                <div class="col-md-4 text-center">
                    Seguidos:&nbsp;
                    <a href="" id="link-comments" class="btn btn-primary" title="Lista de Seguidos" data-toggle="modal" data-target="#listaSeguidos">{{ $seguidos}}</a>
                </div>
                <div class="col-md-4 text-left">
                    <?php $total =0 ?>
                    @foreach ($likes as $like)
                        <?php $total += $like->likes_count ?>
                    @endforeach
                    Likes:&nbsp;
                    <a href="#" id="link-profile" class="btn btn-primary" title="Total de Likes recibidos">{{ $total }}</a>
                </div>
            </div>
            <hr>
            <form action="{{ route('user.edit',['id'=>$data->id]) }}" method="POST" accept-charset="utf-8" id="form-edit">                
                {{ csrf_field() }}
                <div class="form-group">
                    @if ($data->deleted_at==NULL)
                        <div class="row"><!--Fecha Nacimiento, Telefono-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="birthday">Fecha de Nacimiento</label><br>
                                <input type="text" name="birthday" value="{{$data->birthday}}" class="form-control"  disabled>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="phone">Telefono</label><br>
                                <input type="text" name="phone" value="{{$data->phone}}" class="form-control">
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <br>
                        <div class="row"><!--Genero,Tipo de Talento(Musical o Deportivo)-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="gender">Genero</label><br>
                                <input type="text" name="gender" value="{{$data->gender}}" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="talentType">Tipo de Talento</label><br>
                                <select class="form-control" name="talent_type_id" id="talent_type_id" disabled>
                                    @foreach ($talent_type as $type)
                                        @if ($data->talent_type_id==$type->id)
                                            <option value="{{ $type->id }}" selected>{{ $type->talent_type }}</option>
                                        @else
                                            <option value="{{ $type->id }}">{{ $type->talent_type }}</option>
                                        @endif                                        
                                    @endforeach                                    
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <br>
                        <div class="row"><!--Talentos,Estado-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="talents">Talentos</label><br>
                                <div class="multiselect col-xs-6">
                                
                                    <div id="sport-list" >
                                        <div id="selectSports">
                                            <select class="form-control">
                                              <option>--Seleccionar--</option>
                                            </select>
                                            <div class="overSelectSports"></div>
                                        </div>                            
                                        <div id="deportes" class="hide">
                                        @if (sizeof($myTalents)>0)
                                            @foreach ($talentsSport as $sport)                                        
                                                <?php $countTalent = 0; ?>

                                                @foreach ($myTalents as $myTalent)
                                                    @if ($myTalent->id==$sport->id)
                                                         <?php $countTalent = 0; ?>
                                                        @break
                                                    @else
                                                        <?php $countTalent ++; ?>
                                                    @endif                                                
                                                @endforeach
                                                @if ($countTalent==0)
                                                    <label for="{{ $sport->id }}">
                                                    <input type="checkbox" id="{{ $sport->id }}" name="listTalents[]" value="{{ $sport->id }}" checked/>{{ $sport->talent_name }}</label>
                                                @elseif($countTalent==sizeof($myTalents))
                                                    <label for="{{ $sport->id }}">
                                                    <input type="checkbox" id="{{ $sport->id }}" name="listTalents[]" value="{{ $sport->id }}"/>{{ $sport->talent_name }}</label>
                                                @endif
                                            @endforeach                                            
                                        @else
                                            @foreach ($talentsSport as $sport)
                                            <label for="{{ $sport->id }}">
                                              <input type="checkbox" id="{{ $sport->id }}" name="listTalents[]" value="{{ $sport->id }}" />{{ $sport->talent_name }}</label>    
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                
                                    <div id="music-list" style="display: none">
                                        <div id="selectMusic">
                                            <select class="form-control">
                                              <option>--Seleccionar--</option>
                                            </select>
                                            <div class="overSelectMusic"></div>
                                        </div>
                                        <div id="musicales" class="hide">
                                        @if (sizeof($myTalents)>0)
                                            @foreach ($talentsMusic as $music)                                        
                                                <?php $countTalent = 0; ?>

                                                @foreach ($myTalents as $myTalent)
                                                    @if ($myTalent->id==$music->id)
                                                         <?php $countTalent = 0; ?>
                                                        @break
                                                    @else
                                                        <?php $countTalent ++; ?>
                                                    @endif                                                
                                                @endforeach
                                                @if ($countTalent==0)
                                                    <label for="{{ $music->id }}">
                                                    <input type="checkbox" id="{{ $music->id }}" name="listTalents[]" value="{{ $music->id }}" checked/>{{ $music->talent_name }}</label>
                                                @elseif($countTalent==sizeof($myTalents))
                                                    <label for="{{ $music->id }}">
                                                    <input type="checkbox" id="{{ $music->id }}" name="listTalents[]" value="{{ $music->id }}"/>{{ $music->talent_name }}</label>
                                                @endif
                                            @endforeach                                            
                                        @else
                                            @foreach ($talentsMusic as $music)
                                            <label for="{{ $music->id }}">
                                              <input type="checkbox" id="{{ $music->id }}" name="listTalents[]" value="{{ $music->id }}" />{{ $music->talent_name }}</label>    
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                              
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="talents">Estado</label><br>
                                @if ($data->deleted_at==NULL)
                                    <img class="status" src="{{asset('images/activo.png')}}" data-id="{{ $data->id }}" data-toggle="modal" data-target="#desactivateUser">
                                @else
                                    <img  class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $data->id }}" data-toggle="modal" data-target="#activateUser">
                                @endif
                            </div>
                            <div class="col-md-1"></div>
                        </div>                       
                        <br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button type="button" class="btn update-user boton" data-id="{{ $data->id }}">Modificar</button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <br>
                    @else
                       <div class="row"><!--Fecha Nacimiento, Telefono-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="birthday">Fecha de Nacimiento</label><br>
                                <input type="text" name="birthday" value="{{$data->birthday}}" class="form-control"  disabled>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="phone">Telefono</label><br>
                                <input type="text" name="phone" value="{{$data->phone}}" class="form-control" disabled>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <br>
                        <div class="row"><!--Genero,Tipo de Talento(Musical o Deportivo)-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="gender">Genero</label><br>
                                <input type="text" name="gender" value="{{$data->gender}}" class="form-control" disabled>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="talentType">Tipo de Talento</label><br>
                                @if ($data->talent_type_id==1)
                                    <input type="text" name="talentType" value="Musical" class="form-control" disabled>
                                @else
                                    <input type="text" name="talentType" value="Deportivo" class="form-control" disabled>
                                @endif

                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <br>
                        <div class="row"><!--Talentos,Estado-->
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <label for="talents">Talentos</label><br>
                                <input type="text" name="talents" value="listado de talentos" class="form-control" disabled>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label for="talents">Estado</label><br>
                                @if ($data->deleted_at==NULL)
                                    <img class="status" src="{{asset('images/activo.png')}}" data-id="{{ $data->id }}" data-toggle="modal" data-target="#desactivateUser">
                                @else
                                    <img  class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $data->id }}" data-toggle="modal" data-target="#activateUser">
                                @endif
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <br>
                        <strong style="color:red">* Si requiere modificar el usuario, primero tiene que activarlo</strong>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button type="button" class="btn update-user boton" disabled>Modificar</button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <br>                        
                    @endif
                </div>                
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<div class="modal fade" id="listaSeguidores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Seguidores de {{'@'.$data->name.' '.$data->surname}}</h4>                    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="height:300px;overflow-y: scroll;">                    
                <input type="hidden" name="publication_id" id="publication_id" value="">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <table class="table">
                                <tbody>
                                @foreach ($listSeguidores as $seguidor)
                                    <tr class="text-center">
                                        <td>
                                            <img class="photo-profile-user" src="{{ asset('images/'.$seguidor->follower->name.'_'.$seguidor->follower->id.'/'.$seguidor->follower->profile_photo) }}" alt="">
                                        </td>
                                        <td>{{ $seguidor->follower->name.' '.$seguidor->follower->surname }}</td>
                                    </tr>                                           
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn boton" data-dismiss="modal">Cerrar</button>    
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="listaSeguidos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Usuarios Seguidos por {{'@'.$data->name.' '.$data->surname}}</h4>                    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="height:300px;overflow-y: scroll;">
                <input type="hidden" name="publication_id" id="publication_id" value="">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <table class="table">
                                <tbody>
                                @foreach ($listSeguidos as $seguido)
                                    <tr class="text-center">
                                        <td>
                                            <img class="photo-profile-user" src="{{ asset('images/'.$seguido->user->name.'_'.$seguido->user->id.'/'.$seguido->user->profile_photo) }}" alt="">
                                        </td>
                                        <td>{{ $seguido->user->name.' '.$seguido->user->surname }}</td>
                                    </tr>                                           
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn boton" data-dismiss="modal">Cerrar</button>    
            </div>
        </div>
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
<script>
    $(document).ready(function(){

        $('.update-user').click(function(e){

            e.preventDefault();

            var user = $(this).data('id');
            var form = $('#form-edit');
            var route = form.attr('action');
            var data = form.serialize();
            
            $.post(route, data, function(result) {
                alert(result);
            });

        });
        
        function viewTalentList(){

            var talent_type =$('#talent_type_id').val();
            var divSports = $('#sport-list');
            var divMusic = $('#music-list');

            if (talent_type==1) {
                divMusic.show(); 
                divSports.hide();           
            }else {
                divSports.show();
                divMusic.hide();                
            }

        }

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


        window.addEventListener('load',viewTalentList,true);
    });
</script>
@endsection