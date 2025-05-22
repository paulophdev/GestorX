<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | GestorX Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1 0 auto;
        }
        .loja-navbar { background: #111827; color: #fff; }
        .loja-navbar a { color: #fff; font-weight: 600; text-decoration: none; margin-right: 1.5rem; }
        .loja-navbar a:hover { color: #38bdf8; }
        .loja-footer {
            background: #111827;
            color: #fff;
            padding: 2rem 0 1rem 0;
            text-align: center;
            margin-top: auto;
            border-radius: 2rem 2rem 0 0;
            transition: all 0.2s;
        }
        .footer-fixed-bottom {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            border-radius: 2rem 2rem 0 0;
            z-index: 10;
        }
        .loja-footer a { color: #38bdf8; text-decoration: none; }
        .loja-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="loja-navbar d-flex align-items-center px-4 py-3 mb-0">
        <div class="fw-bold fs-4 me-auto">GestorX <span style="color:#38bdf8;">Loja</span></div>
        {{-- <a href="/loja">Produtos</a> --}}
    </nav>
    <div class="container py-4">
        {{-- <h1 class="h3 fw-bold mb-4">Carrinho</h1> --}}
        <div id="cartContent"></div>
    </div>
    <footer class="loja-footer mt-5">
        <div class="mb-2">&copy; {{ date('Y') }} GestorX Loja. Todos os direitos reservados.</div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const cartContent = document.getElementById('cartContent');
        const footer = document.querySelector('.loja-footer');
        if (!cart.length) {
            cartContent.innerHTML = `<div class='text-center text-muted py-5' style='font-size:1.2em;'>
                <div style='font-size:2.5em;'>🛒</div>
                Seu carrinho está vazio.<br>
                <a href='/loja' class='btn btn-outline-primary mt-3'>Voltar para a loja</a>
            </div>`;
            if (footer) footer.classList.add('footer-fixed-bottom');
            return;
        }
        if (footer) footer.classList.remove('footer-fixed-bottom');
        let total = 0;
        cartContent.innerHTML = `
        <div class='table-responsive'>
        <table class='table align-middle bg-white rounded shadow-sm'>
            <thead class='table-light'>
                <tr>
                    <th></th>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Valor unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                ${cart.map(item => {
                    const subtotal = item.price * item.qty;
                    total += subtotal;
                    return `<tr>
                        <td><img src='${item.image}' style='width:60px; height:60px; object-fit:cover; border-radius:0.5rem;'></td>
                        <td>${item.name}</td>
                        <td>${item.qty}</td>
                        <td>R$ ${item.price.toLocaleString('pt-BR', {minimumFractionDigits:2})}</td>
                        <td>R$ ${(subtotal).toLocaleString('pt-BR', {minimumFractionDigits:2})}</td>
                    </tr>`;
                }).join('')}
            </tbody>
        </table>
        </div>
        <div class='d-flex justify-content-end align-items-center gap-3 mt-3'>
            <span class='fs-5 fw-bold'>Total: R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits:2})}</span>
            <button class='btn btn-success btn-lg' disabled>Finalizar Pedido</button>
        </div>
        `;
    }
    document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html> 