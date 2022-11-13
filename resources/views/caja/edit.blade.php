@extends('adminlte::page')

@section('title', 'Arqueo Caja')

@section('content')
@include('sweetalert::alert')
{!! Form::open(['url' => '/caja/'.$caja->id, 'method' => 'PUT']) !!} 
    <div class="col-md-6">   
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Arqueo de Caja</h3>                
            </div> 
            <div class="card-body">                
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                {!! Form::label('fecha', $caja->fecha) !!}                                    
                                <div class="col-sm-4">
                                    {!! Form::label('hora_inicio', $caja->hora_inicio) !!}
                                </div>
                            </div>
                        </div>                                                                        
                        <div class="col-sm">
                            <h5>Cantidad de Billetes</h5>
                            <div class="form-group row">
                                {!! Form::label('Billetes', '200 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('b200', 0, ['id'=>'idb200', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Billetes', '100 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('b100', 0, ['id'=>'idb100', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Billetes', '50 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('b50', 0, ['id'=>'idb50', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Billetes', '20 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('b20', 0, ['id'=>'idb20', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Billetes', '10 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('b10', 0, ['id'=>'idb10', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                        </div>
                        <div class="col-sm">
                            <h5>Cantidad de Monedas</h5>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '5 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m5', 0, ['id'=>'idm5', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '2 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m2', 0, ['id'=>'idm2', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '1 Bs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m1', 0, ['id'=>'idm1', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '50 ctvs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m05', 0, ['id'=>'idm05', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '20 ctvs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m02', 0, ['id'=>'idm02', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Monedas', '10 ctvs',['class'=>'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('m01', 0, ['id'=>'idm01', 'class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'step'=>'1']) !!}
                                </div>                    
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group row">
                            {!! Form::label('Total', 'Total',['class'=>'col-sm-3 col-form-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::number('total', 0, ['id'=>'itotal','class'=>'form-control','placeholder'=>'0','min'=>'0', 'readonly', 'step'=>'any']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                {!! Form::button('<i class="fa fa-calculator"></i> Arqueo', ['class'=>'fomr-control btn btn-info', 'onclick'=>'arqueo()']) !!}
                            </div>                            
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group row">
                                {!! Form::label('Monto', 'Monto de Apertura',['class'=>'col-sm-6 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('monto_apertura', $caja->monto_apertura, ['class'=>'form-control','readonly']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Gastos', 'Total de Gastos',['class'=>'col-sm-6 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('gastos', $total_gasto, ['class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'readonly']) !!}
                                </div>                    
                            </div>
                            <div class="form-group row">
                                {!! Form::label('Ganancias', 'Total de Ganancias',['class'=>'col-sm-6 col-form-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::number('ganancias', $total_ganancia, ['class'=>'form-control','placeholder'=>'0','min'=>'0', 'required', 'readonly']) !!}
                                </div>                    
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <div class="float-left">
                        <a type="submit" class="btn btn-default btn-lg" href="{{url('/caja')}}">Cancelar</a>    
                    </div>
                    <div class="float-right">
                        <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
                    </div>            
                </div>                
            </div>
        </div>            
    </div>
{!! Form::close() !!}
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function arqueo(){
            b200 = document.getElementById('idb200').value;
            if (b200=="") b200=b200.defaultValue;
            b200=b200*200;
            
            b100 = document.getElementById('idb100').value;
            if (b100=="") b100=b100.defaultValue;
            b100=b100*100;

            b50 = document.getElementById('idb50').value;
            if (b50=="") b50=b50.defaultValue;
            b50=b50*50;

            b20 = document.getElementById('idb20').value;
            if (b20=="") b20=b20.defaultValue;
            b20=b20*20;

            b10 = document.getElementById('idb10').value;
            if (b10=="") b10=b10.defaultValue;
            b10=b10*10;

            m5 = document.getElementById('idm5').value;
            if (m5=="") m5=m5.defaultValue;
            m5=m5*5;
            
            m2 = document.getElementById('idm2').value;
            if (m2=="") m2=m2.defaultValue;
            m2=m2*2;

            m1 = document.getElementById('idm1').value;
            if (m1=="") m1=m1.defaultValue;
            m1=m1*1;

            m05 = document.getElementById('idm05').value;
            if (m05=="") m05=m05.defaultValue;
            m05=(m05*0.5).toFixed(2);

            m02 = document.getElementById('idm02').value;
            if (m02=="") m02=m02.defaultValue;
            m02=(m02*0.2).toFixed(2);

            m01 = document.getElementById('idm01').value;
            if (m01=="") m01=m01.defaultValue;
            m01=(m01*0.1).toFixed(2);

            billete=b200+b100+b50+b20+b10;
            moneda=m5+m2+m1+parseFloat(m05)+parseFloat(m02)+parseFloat(m01);
            total=billete+parseFloat(moneda.toFixed(2));
            a=document.getElementById("itotal").value=total;
        }
    </script>
@stop