<x-mail::message>
# Novo Pedido Realizado

**Nome:** {{ $order->nome_cliente }}  
**Telefone:** {{ $order->telefone }}  
**E-mail:** {{ $order->email }}  

**Endereço de Entrega:**  
<pre style="font-family:inherit;">{{ $order->endereco }}</pre>

---

## Itens do Pedido
@foreach($itens as $item)
- {{ $item->quantidade }}x {{ $item->nome_produto }}
@if($item->variacoes)
    @foreach($item->variacoes as $grupo => $valor)
    - {{ $grupo }}: {{ $valor }}
    @endforeach
@endif
(R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}) = R$ {{ number_format($item->subtotal, 2, ',', '.') }}
@endforeach

---

**Subtotal:** R$ {{ number_format($order->subtotal, 2, ',', '.') }}  
**Frete:** R$ {{ number_format($order->frete, 2, ',', '.') }}  
@if($order->desconto > 0)
**Desconto:** -R$ {{ number_format($order->desconto, 2, ',', '.') }}  
@endif
**Total:** R$ {{ number_format($order->total, 2, ',', '.') }}

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
