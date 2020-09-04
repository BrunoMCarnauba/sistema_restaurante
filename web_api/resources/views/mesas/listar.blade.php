@extends('template')

@section('titulo', 'Mesas')

@section('conteudo')
<div class="user-data m-b-30">
        <h3 class="title-3 m-b-30">
            <i class="zmdi zmdi-account-calendar"></i>Mesas Cadastradas</h3>
        

        <div class="table-responsive table-data">
                @if(session('sucesso'))
                <div class="alert alert-success" role="alert" style="margin:0px 10px">
                    {{session('sucesso')}}
                </div>
                @endif
            <table class="table">
                <thead>
                    <tr>
                        <td>ID Mesa</td>
                        <td>Itens</td>
                        <td>Status</td>
                        <td>Opções</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mesas as $mesa)
                    <tr>
                        <!-- ID MESA -->
                        <td><h6>{{$mesa->id_mesa}}</h6></td>
                        <!-- ITENS -->
                        @if($mesa->pedido != null)
                            <td>
                                @foreach($mesa->pedido->itensPedido as $itemPedido)
                                    <div>
                                        <h6 style="float: left; margin-right: 5px;">{{$itemPedido->quantidade}}x {{$itemPedido->produto->nome}}</h6>
                                        <h6 style="font-weight: normal">{{$itemPedido->comentario}}</h6>
                                    </div>
                                    <div style="clear: left;">
                                        <h6 style="float: left; margin-right: 5px">Status: </h6>
                                        @if($itemPedido->status_pronto == true)
                                            <h6 style="color: green;">Feito</h6>
                                        @else
                                            <h6 style="color: DarkGoldenRod;">Aguardando</h6>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                        @else
                            <td><h6>Nenhum</h6></td>
                        @endif
                        <!-- STATUS -->
                        <td><h6>{{$mesa->status}}</h6></td>
                        <!-- OPÇÕES -->   
                        <td>
                            <!--<span class="more remover-modal" data-toggle="modal" data-target="#smallmodal" data-id="{{$mesa->id_mesa}}"><i class="zmdi zmdi-delete"></i></span>-->
                            @if($mesa->pedido != null)
                                <a href="{{route('itens_pedido.visualizar', ['id' => $mesa->id_mesa])}}">
                                    <span class="more"><i class="fa fa-eye"></i></span>
                                </a>
                            @else
                                <span class="more"><i class="fa fa-eye"></i></span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        <!-- Paginação -->
        <div style="padding:10px">{{$mesas->links()}}</div>
        
        </div>
      
    </div>

@push('javascript')
  <!-- modal small -->
  <div class="modal fade" id="smallmodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Remover Mesa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       Deseja Realmente excluir essa mesa?
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
        let mesaID;
        $('.remover-modal').click(function() {
            mesaID = $(this).data('id');
        })

        $('.btn-deletar').click(() => window.location.href="{{route('mesas.excluir')}}/"+mesaID);
    </script>
@endpush
@endsection