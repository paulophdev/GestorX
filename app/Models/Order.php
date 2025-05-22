<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nome_cliente',
        'telefone',
        'email',
        'endereco',
        'subtotal',
        'frete',
        'total',
        'status',
        'cupom_id',
        'desconto',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
} 