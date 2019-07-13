@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row block-search" style="border-bottom: 1px solid #333333">
            <div class="col-md-4">
                <h5 class="title-main">Crear Notificación</h5>
            </div>
        </div>
        <br>
        <br>
        <div class="row justify-content-center" id="form-login">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header title-login">Datos de Notificación</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('notification.save') }}">
                            {{ csrf_field() }}
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="detail">Detalle:</label>
                                    <textarea name="detail"  class="form-control" style="width: 100%"></textarea>
                                </div>
                            </div>
                            <br>    
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-5">
                                    <label for="status">Estado:</label><br>
                                    <select name="status" class="form-control" >
                                        <option value="">--Seleccionar--</option>
                                        <option value="publicada">Publicada</option>
                                        <option value="pendiente">Pendiente</option>
                                    </select>
                                </div>
                            </div> 
                            <br>
                            <br>                      
                            <div class="row justify-content-center">
                                <div class="col-md-10">                
                                    <button style="width: 100%" type="submit" id="boton" class="btn boton">
                                       Crear
                                    </button>                                
                                </div>
                            </div>                       
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endsection