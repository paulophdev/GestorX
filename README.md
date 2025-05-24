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

## Rodando o projeto com Docker

### Pré-requisitos
- Docker e Docker Compose instalados
- Ajuste as variáveis do arquivo `.env` **antes de buildar o projeto**
- O banco de dados deve ser **SQLite**
- Informe os dados no .env para envio de e-mail via SMTP

No seu `.env`, garanta que:
```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
```

### Passos para rodar o projeto

1. Suba o container:
   ```sh
   docker-compose up --build
   ```

2. Instale as dependências PHP:
   ```sh
   docker-compose exec app composer install
   ```

3. Gere a chave da aplicação:
   ```sh
   docker-compose exec app php artisan key:generate
   ```

4. Rode as migrações:
   ```sh
   docker-compose exec app php artisan migrate
   ```

5. Crie o link simbólico do storage:
   ```sh
   docker-compose exec app php artisan storage:link
   ```

6. Acesse o sistema em: [http://localhost:8000](http://localhost:8000)

---

**Observações:**
- Sempre edite o `.env` antes de rodar o build do Docker.
- Para acessar o container manualmente:
  ```sh
  docker-compose exec app bash
  ```