@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row block-search">
            <div class="col-md-2">
                <h5 class="title-main">Publicaciones</h5>
            </div>
            <div class="col-md-4 offset-md-6">
                <form method="get" action="{{route('publication.list')}}" id="searchPublications">
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
                    <th class="text-center">Publicacion</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Id Publicacion</th>
                    <th class="text-center">Comentarios</th>
                    <th class="text-center">Ver Multimedia</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $publication)
                    <tr>
                        <td class="text-center">{{ $publication->description}}</td>
                        <td class="text-center">
                            @if ($publication->deleted_at==NULL)
                                 <img  class="status" src="{{asset('images/activo.png')}}" data-id="{{ $publication->id }}" data-toggle="modal" data-target="#desactivatePublication">
                            @else
                                <img  class="status" src="{{asset('images/inactivo.png')}}" data-id="{{ $publication->id }}" data-toggle="modal" data-target="#activatePublication">
                            @endif
                        </td>
                        <td class="text-center">{{ '@'.$publication->users->name.'_'.$publication->users->surname }}</td>
                        <td class="text-center">{{ $publication->id }}</td>
                        <td class="text-center">
                            @if($publication->comments_count>0)
                                <a id="link-comments" href="{{route('comments.publication',['publication'=>$publication->id,'id'=>$publication->users->id])}}" class="btn btn-success">{{ $publication->comments_count }}</a>
                            @else
                                <a id="link-comments" href="#" class="btn btn-dark">{{ $publication->comments_count }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning" data-url="{{ asset('images/'.$publication->users->name.'_'.$publication->users->id.'/'.$publication->media_file)  }}" data-toggle="modal" data-target="#multimedia"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="clearfix"></div>
            {{ $data->links() }}
        </div>
    </div>
    <div class="modal fade" id="multimedia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Archivo Multimedia Publicado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img style="width: 463px;height:300px;border:2px solid black;" id="publicationMain" src="" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="desactivatePublication" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Control de Publicaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('publication.desactivate','test') }}" method="POST" accept-charset="utf-8">
                    {{method_field('delete')}}
                    {{csrf_field()}}
                    <div class="modal-body">                    
                        <input type="hidden" name="publication_id" id="publication_id" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="modal-title" id="myModalLabel">¿Esta seguro de desactivar ésta publicación?</h5>
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
    <div class="modal fade" id="activatePublication" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Control de Publicaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('publication.activate','test') }}" method="POST" accept-charset="utf-8">
                    {{csrf_field()}}
                    <div class="modal-body">                    
                        <input type="hidden" name="publication_id" id="publication_id" value="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="modal-title" id="myModalLabel">¿Esta seguro de activar ésta publicación?</h5>
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

            $('#activatePublication').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #publication_id').val(id);
            });

            $('#desactivatePublication').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('.modal-body #publication_id').val(id);
            }); 

            $('#multimedia').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var ruta = button.data('url');
                var modal = $(this);
                modal.find('.modal-body #publicationMain')
                .attr("src",ruta);
            });
        });
    </script>
@endsection