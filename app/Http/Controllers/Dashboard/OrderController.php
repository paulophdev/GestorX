<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:novo,confirmado,em_preparo,enviado,entregue,cancelado'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }
} 