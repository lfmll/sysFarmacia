@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.register_message'))
@include('sweetalert::alert')
@section('auth_body')
    <form action="{{ $register_url }}" method="post">
        {{ csrf_field() }}

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name') }}" placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </div>
            @endif
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="{{ __('adminlte::adminlte.password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                   placeholder="{{ __('adminlte::adminlte.retype_password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
            @endif
        </div>
        {{--Asginar Agencia --}}
        <div class="input-group mb-3">
            <select name="agencia" id="agencia" onchange="cargarPuntosVentaP()" required class="form-control {{ $errors->has('agencia') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>Seleccione una Sucursal</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-building {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('agencia'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('agencia') }}</strong>
                </div>
            @endif
        </div>
        {{--Asignar Punto de Venta--}}
        <div class="input-group mb-3">
            <select name="punto_venta" id="puntosVentas" class="form-control {{ $errors->has('punto_venta') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>Seleccione un Punto de Venta</option>                
                {-- Los puntos de venta se cargarán dinámicamente mediante JavaScript basado en la agencia seleccionada --}
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-store {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('punto_venta'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('punto_venta') }}</strong>
                </div>
            @endif
        </div>
        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>

    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $login_url }}">
            {{ __('adminlte::adminlte.i_already_have_a_membership') }}
        </a>
    </p>
@stop
@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    function cargarPuntosVentaP()
    {
        var formData = { agencia: $('#agencia').val() };   
        console.log(formData);     
        $.ajax({
            url: "{{ url('/cargarPuntosVentaP') }}",
            type: "GET",
            data: formData,
            dataType: 'json',
            success: function(data) {
                $('#puntosVentas').empty();
                $('#puntosVentas').append('<option value="">Seleccione un Punto de Venta</option>');
                $.each(data, function(key, value) {
                    $('#puntosVentas').append('<option value="'+ value.id +'">'+ value.nombre +'</option>');
                });
            },
            error: function(data) {
                console.log(data);
                if (data.status == 409) {
                    swal.fire('Error', JSON.parse(data.responseText).mensaje, 'error');                                        
                } else {
                    swal.fire('Error', 'Ocurrió un error al cargar los puntos de venta.', 'error');
                }                
            }
        });
    }
</script>
@stop
