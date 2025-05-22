# GestorX

Sistema de gestão de pedidos e dashboard administrativo.

## Requisitos do Sistema

- PHP 8.4.4
- Node.js v22.11.0

## Instalação

1. Clone o repositório
2. Instale as dependências do PHP:
```bash
composer install
```

3. Instale as dependências do Node:
```bash
npm install
```

4. Inicie o servidor de desenvolvimento:
```bash
composer run dev
```

## URLs do Sistema

- **Loja**: http://localhost:8000
- **Dashboard**: http://localhost:8000/dashbord

## API

### Webhook de Status de Pedidos

Endpoint para atualização do status do pedido:

```
POST /api/v1/pedidos/webhook-status
```

Exemplo de payload:
```json
{
  "id": 123,
  "status": "cancelado"
}
```

### Status de Pedidos

Lista de status possíveis para os pedidos:

| Status | Descrição |
|--------|-----------|
| novo | Pedido recém-criado |
| confirmado | Pagamento ou recebimento confirmado |
| em_preparo | Pedido está sendo preparado |
| enviado | Pedido já saiu para entrega/transporte |
| entregue | Pedido recebido pelo cliente |
| cancelado | Pedido foi cancelado |