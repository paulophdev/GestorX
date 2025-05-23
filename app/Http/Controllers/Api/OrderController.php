<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'telefone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'endereco' => 'required|string',
            'subtotal' => 'required|numeric',
            'frete' => 'required|numeric',
            'desconto' => 'nullable|numeric',
            'cupom_id' => 'nullable|integer|exists:coupons,id',
            'total' => 'required|numeric',
            'itens' => 'required|array|min:1',
            'itens.*.product_id' => 'required|integer|exists:products,id',
            'itens.*.nome_produto' => 'required|string',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco_unitario' => 'required|numeric',
            'itens.*.subtotal' => 'required|numeric',
            'itens.*.variacoes' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'nome_cliente' => $data['nome_cliente'],
                'telefone' => $data['telefone'],
                'email' => $data['email'],
                'endereco' => $data['endereco'],
                'subtotal' => $data['subtotal'],
                'frete' => $data['frete'],
                'cupom_id' => $data['cupom_id'] ?? null,
                'desconto' => $data['desconto'] ?? 0,
                'total' => $data['total'],
                'status' => 'novo',
            ]);
            
            foreach ($data['itens'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'nome_produto' => $item['nome_produto'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                    'subtotal' => $item['subtotal'],
                    'variacoes' => $item['variacoes'] ?? null,
                ]);
                // Abate estoque
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'value' => -$item['quantidade'],
                    'note' => 'Pedido #' . $order->id,
                ]);
            }
            // Atualiza o used_count do cupom, se houver
            if (!empty($data['cupom_id'])) {
                $cupom = \App\Models\Coupon::find($data['cupom_id']);
                if ($cupom) {
                    $cupom->increment('used_count');
                }
            }
            DB::commit();
            // Envia e-mail para o cliente
            Mail::to($order->email)
                ->send(new \App\Mail\PedidoRealizadoMail($order, $order->items));
            return response()->json([
                'order_id' => $order->id,
                'resumo' => $order->items()->get(['nome_produto','quantidade','preco_unitario','subtotal']),
                'total' => $order->total,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao salvar pedido', 'msg' => $e->getMessage()], 500);
        }
    }

    public function webhookStatus(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'status' => 'required|string',
        ]);
        $order = Order::find($data['id']);
        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
        if ($data['status'] === 'cancelado') {
            $order->delete();
            return response()->json(['message' => 'Pedido removido com sucesso.']);
        } else {
            $order->status = $data['status'];
            $order->save();
            return response()->json(['message' => 'Status atualizado com sucesso.']);
        }
    }
} 