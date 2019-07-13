@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row block-search">
            <div class="col-md-2">
                <h5 class="title-main">Comentarios</h5>
            </div>
            <div class="col-md-4 offset-md-6">
                <form method="get" action="{{route('comment.list')}}" id="searchComments">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10">
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
                    <th class="text-center">Comentario</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Id Publicacion</th>
                    <th class="text-center">Id Comentario</th>
                    <th class="text-center">Ver Multimedia</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $comment)
                    <tr>
                        <td class="text-center">{{ $comment->content}}</td>
                        <td class="text-center">
                            @if ($comment->deleted_at==NULL)
                                <img class="status" src="{{asset('images/activo.png')}}" data-id="{{ $comment->id }}" data-toggle="modal" data-target="#desactivateComment">
                            @else
                                 <img class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $comment->id }}" data-toggle="modal" data-target="#activateComment">
                            @endif
                        </td>
                        <td class="text-center">{{ '@'.$comment->users->name.' '.$comment->users->surname }}</td>
                        <td class="text-center">
                            <a id="link-publications" href="{{ route('publication.specific',['id'=>$comment->publication_id]) }}" class="btn btn-success">{{ $comment->publication_id }}</a>
                        </td>
                        <td class="text-center">{{ $comment->id }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning" data-url="{{asset('images/'.$comment->publications->users->name.'_'.$comment->publications->users->id.'/'.$comment->publications->media_file)  }}"  data-toggle="modal" data-target="#multimediaComment"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="clearfix"></div>
            {{ $data->links() }}
        </div>
    </div>
    <div class="modal fade" id="multimediaComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Archivo Multimedia Publicado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img style="width: 463px;height:300px;border:2px solid black;" id="publicationComment" src="" />
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
            
            $('#multimediaComment').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var ruta = button.data('url');
                var modal = $(this);
                modal.find('.modal-body #publicationComment').attr("src",ruta);
            });

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