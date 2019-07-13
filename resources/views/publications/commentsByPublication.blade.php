@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row block-search">
            <div class="col-md-12">
                <h5 class="title-main">Comentarios de Publicación</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" style="border:4px solid #333333">
                <br>
                @foreach ($data as $publication)
                    {{'@'.$user->name.' '.$user->surname.': '.$publication->description}}
                    <br>
                    <br>
                    <img style="width: 100%;height:300px;border:2px solid black;overflow: hidden;float: left;" 
                    src="{{ asset('images/'.$user->name.'_'.$user->id.'/'.$publication->media_file) }}" />
                @endforeach
            </div>
            <div class="col-md-8">
                <div class="row" style="overflow-y:scroll">
                    <table class="table">
                        <thead class="table-title">
                        <tr>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Comentarios</th>
                            <th class="text-center">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $publication)
                            @foreach($publication->comments as $comment)
                                <tr>
                                    <td>
                                        <img style="width: 50px;height:50px;border:2px solid black;overflow: hidden;float: left;" src="{{ asset('images/'.$comment->users->name.'_'.$comment->users->id.'/'.$comment->users->profile_photo) }}" />
                                        &nbsp;
                                        {{ $comment->users->name.' '.$comment->users->surname}}
                                    </td>
                                    <td class="text-center">{{$comment->content}}</td>
                                    <td class="text-center">
                                        @if ($comment->deleted_at==NULL)
                                            <img class="status" src="{{asset('images/activo.png')}}" data-id="{{ $comment->id }}" data-toggle="modal" data-target="#desactivateComment">
                                        @else
                                            <img class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $comment->id }}" data-toggle="modal" data-target="#activateComment">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="desactivateComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Control de Publicaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('comment.desactivate','test') }}" method="POST" accept-charset="utf-8">
                    {{method_field('delete')}}
                    {{csrf_field()}}
                    <div class="modal-body">                    
                        <input type="hidden" name="comment_id" id="comment_id" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="modal-title" id="myModalLabel">¿Esta seguro de desactivar éste comentario?</h5>
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
    <div class="modal fade" id="activateComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Control de Publicaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('comment.activate','test') }}" method="POST" accept-charset="utf-8">
                    {{csrf_field()}}
                    <div class="modal-body">                    
                        <input type="hidden" name="comment_id" id="comment_id" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="modal-title" id="myModalLabel">¿Esta seguro de Activar éste comentario?</h5>
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
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#activateComment').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #comment_id').val(id);
            });

            $('#desactivateComment').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #comment_id').val(id);
            }); 

        });
    </script>
@endsection