@extends('template')

@section('titulo', 'Edição de Funcionário')

@section('conteudo')


<div class="card">
    <div class="card-header">
        <strong>Edição</strong>
    </div>

    <form action="{{route('funcionarios.editar', ['id' => $funcionario->id_funcionario])}}" method="post">
        
        <div class="card-body card-block">
            <!-- FORMULARIO -->
            @include('funcionarios._shared.form')
            <!-- FORMULARIO -->
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Editar
            </button>
        </div>
    </form>
</div>
@endsection