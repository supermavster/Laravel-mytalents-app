@extends('layouts.app')

@section('content')
    <div class="main-users">
        <div class="row block-search">
            <div class="col-md-4">
                <h5 class="title-main">Comentarios de {{'@'.$user->name.' '.$user->surname}}</h5>
            </div>
            <div class="col-md-3 offset-md-5">
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
        <table class="table">
            <thead class="table-title">
            <tr>
                <th class="text-center">Detalle</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Id Publicacion</th>
                <th class="text-center">ID Comentario</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $comment)
                <tr>
                    <td class="text-center">{{ $comment->content}}</td>
                    <td class="text-center">
                        @if ($comment->deleted_at==NULL)
                            <img class="status" src="{{asset('images/activo.png')}}" data-userId="{{ $user->id }}" data-userName="{{ $user->name.' '.$user->surname }}" data-toggle="modal" data-target="#desactivateUser">
                        @else
                            <img class="status" src="{{asset('images/inactivo.png')}}" data-userId="{{ $user->id }}" data-userName="{{ $user->name.' '.$user->surname }}" data-toggle="modal" data-target="#desactivateUser">
                        @endif
                    </td>
                    <td class="text-center">                            
                            <a id="link-publications" href="{{ route('publication.specific',['id'=>$comment->publication_id])  }}" class="btn btn-success">{{ $comment->publication_id }}</a>
                    </td>
                    <td class="text-center">{{ $comment->user_id }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection