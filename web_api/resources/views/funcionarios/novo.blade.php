@extends('template')

@section('titulo', 'Novo Funcionário')

@section('conteudo')


<div class="card">
    <div class="card-header">
        <strong>Cadastro de Funcionário</strong>
    </div>

    <form action="{{route('funcionarios.cadastrar')}}" method="post">
        
        <div class="card-body card-block">
            <!-- FORMULARIO -->
            @include('funcionarios._shared.form')
            <!-- FORMULARIO -->
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Cadastrar
            </button>
        </div>
    </form>
</div>
@endsection