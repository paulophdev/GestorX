@extends('dashboard.layout')

@section('title', 'Detalhes do Pedido')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pedido #{{ $order->id }}</h1>
        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Detalhes do Pedido -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detalhes do Pedido</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Cliente</h6>
                            <p>
                                {{ $order->nome_cliente }}<br>
                                {{ $order->telefone }}<br>
                                {{ $order->email }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Endereço de Entrega</h6>
                            <p>{!! nl2br(e($order->endereco)) !!}</p>
                        </div>
                    </div>

                    <h6 class="font-weight-bold mb-3">Itens do Pedido</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Qtd</th>
                                    <th>Valor Unit.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->nome_produto }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                @if($order->desconto > 0)
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Desconto:@if($order->coupon) ({{ $order->coupon->code }})@endif</strong></td>
                                    <td>- R$ {{ number_format($order->desconto, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Frete:</strong></td>
                                    <td>R$ {{ number_format($order->frete, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status do Pedido -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status do Pedido</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Atualizar Status</label>
                            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                <option value="novo" {{ $order->status === 'novo' ? 'selected' : '' }}>Novo</option>
                                <option value="confirmado" {{ $order->status === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="em_preparo" {{ $order->status === 'em_preparo' ? 'selected' : '' }}>Em Preparo</option>
                                <option value="enviado" {{ $order->status === 'enviado' ? 'selected' : '' }}>Enviado</option>
                                <option value="entregue" {{ $order->status === 'entregue' ? 'selected' : '' }}>Entregue</option>
                                <option value="cancelado" {{ $order->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </form>

                    <div class="mt-4">
                        <h6 class="font-weight-bold">Informações Adicionais</h6>
                        <p class="mb-1">
                            <strong>Data do Pedido:</strong><br>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-1">
                            <strong>Última Atualização:</strong><br>
                            {{ $order->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 