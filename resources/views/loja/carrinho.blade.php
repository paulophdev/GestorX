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
        <div class='mx-auto' style='max-width:900px;'>
        <div class='card shadow-sm mb-4'>
            <div class='card-body p-4'>
                <div class='table-responsive mb-0'>
                    <table class='table align-middle bg-white rounded'>
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
                <div class='d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3'>
                    <span class='fs-5 fw-bold'>Total: R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits:2})}</span>
                    <button class='btn btn-danger' onclick='limparCarrinho()'>Limpar Carrinho</button>
                </div>
            </div>
        </div>
        <!-- Bloco de dados do cliente -->
        <div class='card mb-4'>
            <div class='card-body'>
                <h5 class='card-title mb-3'>Seus Dados</h5>
                <form id='clienteForm' autocomplete='off'>
                    <div class='row g-2 mb-2'>
                        <div class='col-md-4'>
                            <label class='form-label'>Nome</label>
                            <input type='text' class='form-control' id='cliente_nome' required>
                        </div>
                        <div class='col-md-4'>
                            <label class='form-label'>Telefone</label>
                            <input type='tel' class='form-control' id='cliente_telefone' required placeholder="(99) 99999-9999">
                        </div>
                        <div class='col-md-4'>
                            <label class='form-label'>E-mail</label>
                            <input type='email' class='form-control' id='cliente_email' required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Bloco de endereço -->
        <div class='card mb-4 mx-auto' style='max-width:900px;'>
            <div class='card-body'>
                <h5 class='card-title mb-3'>Endereço de Entrega</h5>
                <form id='enderecoForm' autocomplete='off'>
                    <div class='row g-2 mb-2'>
                        <div class='col-4'>
                            <label class='form-label'>CEP</label>
                            <input type='text' class='form-control' id='cep' maxlength='9' placeholder='Digite o CEP' required>
                        </div>
                        <div class='col-4'>
                            <label class='form-label'>Número</label>
                            <input type='text' class='form-control' id='numero' required>
                        </div>
                        <div class='col-4'>
                            <label class='form-label'>Endereço</label>
                            <input type='text' class='form-control' id='endereco' required>
                        </div>
                    </div>
                    <div class='row g-2 mb-2'>
                        <div class='col-4'>
                            <label class='form-label'>Bairro</label>
                            <input type='text' class='form-control' id='bairro' required>
                        </div>
                        <div class='col-4'>
                            <label class='form-label'>Cidade</label>
                            <input type='text' class='form-control' id='cidade' required>
                        </div>
                        <div class='col-4'>
                            <label class='form-label'>Estado</label>
                            <input type='text' class='form-control' id='estado' required>
                        </div>
                    </div>
                    <div class='row g-2 mb-2'>
                        <div class='col-6'>
                            <label class='form-label'>Complemento</label>
                            <input type='text' class='form-control' id='complemento'>
                        </div>
                        <div class='col-6'>
                            <label class='form-label'>Ponto de Referência</label>
                            <input type='text' class='form-control' id='referencia'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Bloco de pagamento -->
        <div class='card mb-4 mx-auto' style='max-width:900px;'>
            <div class='card-body'>
                <h5 class='card-title mb-3'>Resumo do Pagamento</h5>
                <div class='d-flex flex-wrap justify-content-end gap-4 mb-2' style='font-size:1.15em;'>
                    <div class='d-flex gap-2 align-items-center'>
                        <span class='text-muted'>Subtotal:</span>
                        <span class='fw-bold' id='resumoSubtotal'>R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits:2})}</span>
                    </div>
                    <div class='d-flex gap-2 align-items-center'>
                        <span class='text-muted'>Frete:</span>
                        <span class='fw-bold' id='freteResumo'></span>
                    </div>
                </div>
                <hr>
                <div class='text-center mt-4 mb-2'>
                    <span class='fs-3 fw-bold'>Total Geral:&nbsp; <span id='totalGeralResumo'></span></span>
                </div>
            </div>
        </div>
        <div class='text-center mb-4'>
            <button class='btn btn-success btn-lg px-5' style='min-width:220px;' id='btnFinalizarPedido' disabled>Finalizar Pedido</button>
        </div>
        </div>
        `;
        // Calcular e exibir frete e total geral
        let frete = 20;
        if (total >= 52 && total <= 166.59) {
            frete = 15;
        } else if (total > 200) {
            frete = 0;
        }
        const freteResumo = document.getElementById('freteResumo');
        const totalGeralResumo = document.getElementById('totalGeralResumo');
        const resumoSubtotal = document.getElementById('resumoSubtotal');
        if (freteResumo && totalGeralResumo && resumoSubtotal) {
            freteResumo.textContent = frete === 0 ? 'Grátis' : `R$ ${frete.toLocaleString('pt-BR', {minimumFractionDigits:2})}`;
            totalGeralResumo.textContent = `R$ ${(total + frete).toLocaleString('pt-BR', {minimumFractionDigits:2})}`;
            resumoSubtotal.textContent = `R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits:2})}`;
        }
    }
    function limparCarrinho() {
        localStorage.removeItem('cart');
        renderCart();
    }
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
        // Máscara de CEP
        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.addEventListener('input', function() {
                let v = this.value.replace(/\D/g, '');
                if (v.length > 5) v = v.replace(/(\d{5})(\d{1,3})/, '$1-$2');
                this.value = v;
            });
            cepInput.addEventListener('blur', async function() {
                const cep = this.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    try {
                        const resp = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                        const data = await resp.json();
                        if (!data.erro) {
                            document.getElementById('endereco').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';
                        }
                    } catch (e) {}
                }
            });
        }
        // Máscara de telefone
        const telInput = document.getElementById('cliente_telefone');
        if (telInput) {
            telInput.addEventListener('input', function() {
                let v = this.value.replace(/\D/g, '');
                if (v.length > 10) v = v.replace(/(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                else if (v.length > 6) v = v.replace(/(\d{2})(\d{4,5})(\d{0,4})/, '($1) $2-$3');
                else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                else v = v.replace(/(\d{0,2})/, '($1');
                this.value = v;
            });
        }
        // Habilitar botão de finalizar pedido só se todos obrigatórios estiverem preenchidos
        const obrigatorios = ['cep','numero','endereco','bairro','cidade','estado','cliente_nome','cliente_telefone','cliente_email'];
        const btnFinalizar = document.getElementById('btnFinalizarPedido');
        obrigatorios.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', checkEnderecoFields);
            }
        });
        function checkEnderecoFields() {
            let ok = true;
            obrigatorios.forEach(id => {
                const el = document.getElementById(id);
                if (!el || !el.value.trim()) ok = false;
            });
            btnFinalizar.disabled = !ok;
        }
    });
    </script>
</body>
</html> 