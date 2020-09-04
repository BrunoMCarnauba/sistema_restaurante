@extends('template')

@section('titulo', 'Pedidos')

@section('conteudo')
<div class="user-data m-b-30">
        <h3 class="title-3 m-b-30">
            <i class="zmdi zmdi-account-calendar"></i>Pedidos Cadastrados</h3>
        

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
                        <td>Itens</td>
                        <td>Valor total</td>
                        <td>Status</td>
                        <td>Opções</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <!-- ID -->
                        <td><h6>{{$pedido->id_pedido}}</h6></td>
                        <!-- ITENS -->
                        <td>
                            @foreach($pedido->itensPedido as $itemPedido)
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
                        <!-- VALOR TOTAL -->
                        <td><h6>R$: {{number_format($pedido->valor_total, 2, ',', ".")}}</h6></td>
                        <!-- STATUS -->
                        <td>
                            @if($pedido->status_ativo == 1)
                                <h6>Ativo</h6>
                            @else
                                <h6>Finalizado</h6>
                            @endif
                        </td>
                        <!-- OPÇÕES -->   
                        <td>
                            <span class="more remover-modal" data-toggle="modal" data-target="#smallmodal" data-id="{{$pedido->id_pedido}}"><i class="zmdi zmdi-delete"></i></span>               
<!--                            <a href="{{route('itens_pedido.visualizar', ['id' => $pedido->id_pedido])}}">
                                <span class="more"><i class="fa fa-eye"></i></span>
                            </a>-->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        <!-- Paginação -->
        <div style="padding:10px">{{$pedidos->links()}}</div>
        
        </div>
      
    </div>

@push('javascript')
  <!-- modal small -->
  <div class="modal fade" id="smallmodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Remover Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       Deseja Realmente excluir esse pedido?
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
        let pedidoID;
        $('.remover-modal').click(function() {
            pedidoID = $(this).data('id');
        })

        $('.btn-deletar').click(() => window.location.href="{{route('pedidos.excluir')}}/"+pedidoID);
    </script>
@endpush
@endsection