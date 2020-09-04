@extends('template')

@section('titulo', 'Funcionários')

@section('conteudo')
<div class="user-data m-b-30">
        <h3 class="title-3 m-b-30">
            <i class="zmdi zmdi-account-calendar"></i>Funcionários Cadastrados</h3>
        

        <div class="table-responsive table-data">
                @if(session('sucesso'))
                <div class="alert alert-success" role="alert" style="margin:0px 10px">
                    {{session('sucesso')}}
                </div>
                @endif
            <table class="table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nome</td>
                        <td>Cargo</td>
                        <td>Opções</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($funcionarios as $funcionario)
                    <tr>
                        <!-- ID -->
                        <td><h6>{{$funcionario->id_funcionario}}</h6></td>
                        <!-- NOME -->
                        <td>
                            <div class="table-data__info">
                                <h6>{{$funcionario->nome}}</h6>
                                <span>
                                    <a href="#">{{$funcionario->email}}</a>
                                </span>
                            </div>
                        </td>
                        <!-- ADMIN -->
                        <td>
                            <h6>{{$funcionario->cargo->nome}}</h6>
                        </td>
                        <!-- OPÇÕES -->   
                        <td>
                            <a href="{{route('funcionarios.edicao', ['id' => $funcionario->id_funcionario])}}">
                                <span class="more"><i class="zmdi zmdi-edit"></i></span>
                            </a>
                            <span class="more remover-modal" data-toggle="modal" data-target="#smallmodal" data-id="{{$funcionario->id_funcionario}}"><i class="zmdi zmdi-delete"></i></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        <!-- Paginação -->
        <div style="padding:10px">{{$funcionarios->links()}}</div>
        
        </div>
      
    </div>


    @push('javascript')
  <!-- modal small -->
  <div class="modal fade" id="smallmodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Remover Funcionário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       Deseja Realmente excluir esse funcionário?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-deletar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal small -->

    <script>
        let funcionarioID;
        $('.remover-modal').click(function() {
            funcionarioID = $(this).data('id');
        })
            
        $('.btn-deletar').click(() => window.location.href="{{route('funcionarios.excluir')}}/"+funcionarioID);
    </script>
@endpush
@endsection