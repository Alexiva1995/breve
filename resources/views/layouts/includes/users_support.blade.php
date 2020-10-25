<style>
    .contenedor{
        width:150px;
        height:60px;
        position:absolute;
        right:0px;
        bottom:0px;
    }
    .botonF1{
        width:120px;
        height:50px;
        border-radius:100%;
        right:0;
        bottom:0;
        position:fixed;
        margin-right:16px;
        margin-bottom:16px;
        border:none;
        outline:none;
        color:#FFF;
        font-size:28px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        transition:.3s;  
    }
    span{
        transition:.5s;  
    }
</style>


<!-- Default dropup button -->
<div class="btn-group dropup botonF1">
    <button type="button" class="btn btn-info  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        Chat <i class="fa fa-comment"></i>
        @if (count($notificaciones) > 0)
            <span class="badge badge-success">{{count($notificaciones)}}</span>
        @endif
    </button>
    <div class="dropdown-menu">
        @if (Auth::user()->role_id != 3)
            <h6 class="dropdown-header">Usuarios Disponibles</h6>
            @foreach ($usuarios as $user)
            <a class="dropdown-item" href="{{route('messenger', $user->id)}}">{{$user->name}}</a>
            @endforeach
        @endif
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header">Nuevos Mensajes</h6>
        @foreach ($notificaciones as $noti)
            <a class="dropdown-item" href="{{route('messenger', $noti['iduser'])}}">{{$noti['name_user']}}</a>
        @endforeach
    </div>
</div>
