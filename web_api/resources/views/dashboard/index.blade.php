@extends('template')

@section('titulo', 'Dashboard')

@section('conteudo')
<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Visão Geral</h2>
        </div>
    </div>
</div>
<div class="row m-t-25">
    <!-- USUARIOS -->
    <div class="col-sm-6 col-lg-3">
        <div class="overview-item overview-item--c1">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="zmdi zmdi-account-o"></i>
                    </div>
                    <div class="text">
                        <h2>{{$funcionariosCadastrados}}</h2>
                        <span>Funcionários Cadastrados</span>
                    </div>
                </div>
                <div class="overview-chart">
                    <canvas id="widgetChart1"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- PEDIDOS -->
    <div class="col-sm-6 col-lg-3">
        <div class="overview-item overview-item--c4">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="text">
                        <h2>{{$pedidosCadastrados}}</h2>
                        <span>Total de Pedidos</span>
                    </div>
                </div>
                <div class="overview-chart">
                    <canvas id="widgetChart4"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- PEDIDOS ATIVOS -->
    <div class="col-sm-6 col-lg-3">
        <div class="overview-item overview-item--c2">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="text">
                        <h2>{{$pedidosAtivos}}</h2>
                        <span>Pedidos Ativos</span>
                    </div>
                </div>
                <div class="overview-chart">
                    <canvas id="widgetChart4"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection