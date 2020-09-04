@extends('template')

@section('titulo', 'Itens da mesa')

@section('conteudo')
<div class="user-data m-b-30">
        <h3 class="title-3 m-b-30">
            <i class="zmdi zmdi-account-calendar"></i>Itens mesa 0{{$mesa->id_mesa}}</h3>
        

        <div class="table-responsive table-data">
                @if(session('sucesso'))
                <div class="alert alert-success" role="alert" style="margin:0px 10px">
                    {{session('sucesso')}}
                </div>
                @endif
            <table class="table">
                <thead>
                    <tr>
                        <td>Quantidade</td>
                        <td>Nome do produto</td>
                        <td>Valor total</td>
                        <td>Status</td>
                        <td>Opções</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itensPedido as $item)
                    <tr>
                        <!-- QUANTIDADE -->
                        <td><h6>{{$item->quantidade}}</h6></td>
                        <!-- NOME DO PRODUTO E COMENTÁRIO -->
                        <td>
                            <h6>{{$item->quantidade}}x {{$item->produto->nome}}</h6>
                            <h6 style="font-weight: normal">{{$item->comentario}}</h6>
                        </td>
                        <!-- VALOR TOTAL -->
                        <td><h6>R$: {{number_format($item->produto->preco, 2, ',', ".")}}</h6></td>
                        <!-- STATUS -->
                        <td>
                            @if($item->status_pronto == true)
                                <h6 style="color: green;">Feito</h6>
                            @else
                                <h6 style="color: DarkGoldenRod;">Aguardando</h6>
                            @endif
                        </td>
                        <!-- OPÇÕES -->   
                        <td>
                            <span class="more remover-modal" data-toggle="modal" data-target="#smallmodal" data-id="{{$item->id_item_pedido}}"><i class="zmdi zmdi-delete"></i></span>               
                            <a href="{{route('itens_pedido.mudarStatus', ['idMesa' => $mesa->id_mesa, 'idItem' => $item->id_item_pedido])}}">
                                <span class="more"><i class="fa fa-thumbs-up"></i></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        <!-- Paginação -->
        <div style="padding:10px">{{$itensPedido->links()}}</div>
        
        </div>
      
    </div>

@push('javascript')
  <!-- modal small -->
  <div class="modal fade" id="smallmodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Remover Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       Deseja realmente excluir esse item?
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
        let itemPedidoID;
        $('.remover-modal').click(function() {
            itemPedidoID = $(this).data('id');
            console.log(itemPedidoID);
        });
        
        $('.btn-deletar').click(() => window.location.href="{{route('itens_pedido.excluir', ['idMesa' => $mesa->id_mesa])}}/"+itemPedidoID); // window.location.href="{{route('pedidos.excluir')}}/"+itemPedidoID);
    </script>
@endpush
@endsection