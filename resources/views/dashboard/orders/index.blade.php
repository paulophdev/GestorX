@extends('dashboard.layout')

@section('title', 'Pedidos')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pedidos</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                {{ $order->nome_cliente }}<br>
                                <small class="text-muted">{{ $order->telefone }}</small>
                            </td>
                            <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'novo' ? 'primary' : 
                                    ($order->status === 'confirmado' ? 'info' : 
                                    ($order->status === 'em_preparo' ? 'warning' : 
                                    ($order->status === 'enviado' ? 'secondary' : 
                                    ($order->status === 'entregue' ? 'success' : 'danger')))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('dashboard.orders.show', $order) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 