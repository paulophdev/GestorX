<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja | GestorX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Open Sans', Arial, sans-serif; background: #f3f4f6; }
        .loja-navbar { background: #111827; color: #fff; }
        .loja-navbar a { color: #fff; font-weight: 600; text-decoration: none; margin-right: 1.5rem; }
        .loja-navbar a:hover { color: #38bdf8; }
        .loja-banner {
            background: linear-gradient(90deg, #38bdf8 0%, #6366f1 100%);
            color: #fff;
            padding: 3rem 0 2rem 0;
            text-align: center;
            border-radius: 0 0 2rem 2rem;
            margin-bottom: 2rem;
        }
        .loja-banner h1 { font-size: 2.5rem; font-weight: 700; }
        .loja-banner p { font-size: 1.2rem; margin-top: 1rem; }
        .loja-footer {
            background: #111827;
            color: #fff;
            padding: 2rem 0 1rem 0;
            text-align: center;
            margin-top: 3rem;
            border-radius: 2rem 2rem 0 0;
        }
        .loja-footer a { color: #38bdf8; text-decoration: none; }
        .loja-footer a:hover { text-decoration: underline; }
        .input-qtd {
            width: 50px !important;
            outline: none !important;
            box-shadow: none !important;
            border: 1px solid #d1d5db;
        }
        .input-qtd:focus { outline: none !important; box-shadow: none !important; }
    </style>
</head>
<body>
    <!-- Menu externo -->
    <nav class="loja-navbar d-flex align-items-center px-4 py-3 mb-0">
        <div class="fw-bold fs-4 me-auto">GestorX <span style="color:#38bdf8;">Loja</span></div>
        {{-- <a href="/loja">Produtos</a> --}}
        {{-- <a href="#contato">Contato</a> --}}
    </nav>

    <!-- Banner visual -->
    <section class="loja-banner">
        <h1>Bem-vindo à nossa Loja!</h1>
        <p>Confira nossos produtos disponíveis. Qualidade, preço justo e entrega rápida para você!</p>
    </section>

    <div class="container">
        <!-- Skeletons de loading -->
        <div id="lojaSkeletons" class="row g-4">
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 placeholder-glow" style="border-radius:1rem;">
                    <div class="placeholder" style="height:200px; border-radius:1rem 1rem 0 0; background:#222; display:block;"></div>
                    <div class="card-body">
                        <div class="placeholder col-8 mb-2" style="height:2rem;"></div>
                        <div class="placeholder col-6 mb-2" style="height:1.2rem;"></div>
                        <div class="placeholder col-4 mb-2" style="height:1.2rem;"></div>
                        <div class="d-flex gap-2 mt-3">
                            <div class="placeholder rounded-pill col-4" style="height:2.2rem;"></div>
                            <div class="placeholder rounded-pill col-6" style="height:2.2rem;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 placeholder-glow" style="border-radius:1rem;">
                    <div class="placeholder" style="height:200px; border-radius:1rem 1rem 0 0; background:#222; display:block;"></div>
                    <div class="card-body">
                        <div class="placeholder col-8 mb-2" style="height:2rem;"></div>
                        <div class="placeholder col-6 mb-2" style="height:1.2rem;"></div>
                        <div class="placeholder col-4 mb-2" style="height:1.2rem;"></div>
                        <div class="d-flex gap-2 mt-3">
                            <div class="placeholder rounded-pill col-4" style="height:2.2rem;"></div>
                            <div class="placeholder rounded-pill col-6" style="height:2.2rem;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 placeholder-glow" style="border-radius:1rem;">
                    <div class="placeholder" style="height:200px; border-radius:1rem 1rem 0 0; background:#222; display:block;"></div>
                    <div class="card-body">
                        <div class="placeholder col-8 mb-2" style="height:2rem;"></div>
                        <div class="placeholder col-6 mb-2" style="height:1.2rem;"></div>
                        <div class="placeholder col-4 mb-2" style="height:1.2rem;"></div>
                        <div class="d-flex gap-2 mt-3">
                            <div class="placeholder rounded-pill col-4" style="height:2.2rem;"></div>
                            <div class="placeholder rounded-pill col-6" style="height:2.2rem;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 placeholder-glow" style="border-radius:1rem;">
                    <div class="placeholder" style="height:200px; border-radius:1rem 1rem 0 0; background:#222; display:block;"></div>
                    <div class="card-body">
                        <div class="placeholder col-8 mb-2" style="height:2rem;"></div>
                        <div class="placeholder col-6 mb-2" style="height:1.2rem;"></div>
                        <div class="placeholder col-4 mb-2" style="height:1.2rem;"></div>
                        <div class="d-flex gap-2 mt-3">
                            <div class="placeholder rounded-pill col-4" style="height:2.2rem;"></div>
                            <div class="placeholder rounded-pill col-6" style="height:2.2rem;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim skeletons -->
        <div id="lojaProductsList" class="row g-4"></div>
        <div id="lojaNoProductsMsg" class="d-none mt-5">
            <li class="list-group-item text-center text-muted py-5" style="font-size:1.2em; border:none; background:transparent; list-style:none;">
                <div style="font-size:2.5em;">🛒</div>
                <div class="mt-2">Nenhum produto disponível na loja.</div>
            </li>
        </div>
    </div>

    <!-- Footer -->
    <footer class="loja-footer mt-5">
        <div class="mb-2">&copy; {{ date('Y') }} GestorX Loja. Todos os direitos reservados.</div>
        {{-- <div id="contato">Dúvidas? <a href="mailto:contato@gestorx.com">contato@gestorx.com</a></div> --}}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadLojaProducts();
    });

    async function loadLojaProducts() {
        try {
            const response = await fetch('/api/v1/produtos');
            const products = await response.json();
            const list = document.getElementById('lojaProductsList');
            const noProductsMsg = document.getElementById('lojaNoProductsMsg');
            const skeletons = document.getElementById('lojaSkeletons');
            if (products.length === 0) {
                list.innerHTML = '';
                noProductsMsg.classList.remove('d-none');
                if (skeletons) skeletons.style.display = 'none';
            } else {
                noProductsMsg.classList.add('d-none');
                // Para cada produto, buscar o total em estoque
                const estoquePromises = products.map(async (product) => {
                    const estoqueResp = await fetch(`/api/v1/produtos/${product.id}/estoque`);
                    const movimentos = await estoqueResp.json();
                    let total = 0;
                    movimentos.forEach(mov => { total += parseInt(mov.value, 10); });
                    return { ...product, estoque: total };
                });
                const produtosComEstoque = await Promise.all(estoquePromises);
                // Monta o HTML dos cards
                const cardsHtml = produtosComEstoque.map(product => `
                    <div class=\"col-md-4 col-lg-3\">
                      <div class=\"card h-100\" style=\"border:1px solid #e5e7eb; border-radius:1rem; background:#fff; box-shadow:none;\">
                        <img src=\"${product.image ? '/' + product.image : 'https://via.placeholder.com/300x200?text=Sem+Imagem'}\" class=\"card-img-top\" alt=\"${product.name}\" style=\"border-radius:1rem 1rem 0 0;\">
                        <div class=\"card-body d-flex flex-column\">
                          <h5 class=\"card-title\" style=\"color:#111827; font-weight:600;\">${product.name}</h5>
                          <p class=\"card-text mb-2\" style=\"color:#6b7280; font-size:0.95em;\">${product.description ? product.description : ''}</p>
                          <p class=\"card-text text-muted mb-2\" style=\"color:#6b7280;\">R$ ${Number(product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                          ${product.variations && product.variations.length > 0 ? `
                            <div class=\"mb-3\">
                              ${Object.entries(product.variations.reduce((acc, v) => {
                                const group = v.group || '';
                                if (!acc[group]) acc[group] = [];
                                acc[group].push(v.name);
                                return acc;
                              }, {})).map(([group, options]) => `
                                <div class=\"mb-2\">
                                  <label class=\"form-label mb-1\" style=\"font-size:0.9em; color:#4b5563;\">${group}</label>
                                  <select class=\"form-select form-select-sm\" id=\"var_${product.id}_${group.replace(/\s+/g, '_')}\">
                                    <option value=\"\">Selecione...</option>
                                    ${options.map(opt => `<option value=\"${opt}\">${opt}</option>`).join('')}
                                  </select>
                                </div>
                              `).join('')}
                            </div>
                          ` : ''}
                          <div class=\"mb-2\">
                            <span class=\"badge ${product.estoque > 0 ? 'bg-success' : 'bg-danger'}\">
                              ${product.estoque > 0 ? 'Em estoque' : 'Sem estoque'}
                            </span>
                          </div>
                          <div class=\"mt-auto d-flex justify-content-end align-items-center gap-2\">
                            ${product.estoque > 0 ? `
                              <button class=\"btn btn-outline-secondary btn-sm btn-qty\" data-action=\"minus\" data-id=\"${product.id}\">-</button>
                              <input type=\"number\" class=\"form-control form-control-sm text-center input-qtd\" style=\"width:55px;\" min=\"1\" max=\"${product.estoque}\" value=\"1\" id=\"qty_${product.id}\" readonly>
                              <button class=\"btn btn-outline-secondary btn-sm btn-qty\" data-action=\"plus\" data-id=\"${product.id}\">+</button>
                            ` : ''}
                            <button class=\"btn btn-primary w-100\" ${product.estoque > 0 ? '' : 'disabled'} data-id=\"${product.id}\" onclick=\"adicionarAoCarrinho(${product.id})\" id=\"btnCart_${product.id}\">
                              R$ <span id=\"btnCartValue_${product.id}\">${Number(product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                `).join('');
                // Pré-carrega todas as imagens
                const images = [];
                produtosComEstoque.forEach(product => {
                    const src = product.image ? '/' + product.image : 'https://via.placeholder.com/300x200?text=Sem+Imagem';
                    images.push(new Promise(resolve => {
                        const img = new window.Image();
                        img.onload = () => resolve();
                        img.onerror = () => resolve();
                        img.src = src;
                    }));
                });
                await Promise.all(images);
                // Só agora mostra os cards e esconde os skeletons
                list.innerHTML = cardsHtml;
                if (skeletons) skeletons.style.display = 'none';
                // Adiciona listeners para os botões + e - e atualiza valores
                const updateBtnCartValue = (id, price) => {
                    const input = document.getElementById('qty_' + id);
                    const span = document.getElementById('btnCartValue_' + id);
                    if (input && span) {
                        const qtd = parseInt(input.value);
                        span.textContent = (qtd * price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                };
                document.querySelectorAll('.btn-qty').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const input = document.getElementById('qty_' + id);
                        const max = parseInt(input.getAttribute('max'));
                        let val = parseInt(input.value);
                        if (this.getAttribute('data-action') === 'plus') {
                            if (val < max) input.value = val + 1;
                        } else {
                            if (val > 1) input.value = val - 1;
                        }
                        // Atualiza valor do botão
                        const price = Number(this.closest('.card-body').querySelector('.card-text.text-muted').textContent.replace('R$','').replace(',','.'));
                        updateBtnCartValue(id, price);
                    });
                });
            }
        } catch (error) {
            console.error('Erro ao carregar produtos da loja:', error);
        }
    }

    // Adiciona função global para simular adicionar ao carrinho
    window.adicionarAoCarrinho = function(id) {
        const input = document.getElementById('qty_' + id);
        const qtd = input ? parseInt(input.value) : 1;
        // Pega dados do produto no card
        const card = input.closest('.card');
        const name = card.querySelector('.card-title').textContent;
        const price = Number(card.querySelector('.card-text.text-muted').textContent.replace('R$','').replace(',','.'));
        const image = card.querySelector('img').src;
        
        // Coleta as variações selecionadas
        const variations = {};
        card.querySelectorAll('select[id^="var_' + id + '_"]').forEach(select => {
            const group = select.id.replace('var_' + id + '_', '').replace(/_/g, ' ');
            const value = select.value;
            if (value) {
                variations[group] = value;
            }
        });

        // Monta item
        const item = { 
            id, 
            name, 
            price, 
            qty: qtd, 
            image,
            variations: Object.keys(variations).length > 0 ? variations : null
        };

        // Salva no localStorage (pode acumular se já existir)
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const idx = cart.findIndex(i => i.id == id && JSON.stringify(i.variations) === JSON.stringify(variations));
        if (idx >= 0) {
            cart[idx].qty += qtd;
        } else {
            cart.push(item);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        // Redireciona para o carrinho
        window.location.href = '/carrinho';
    }
    </script>
</body>
</html> 