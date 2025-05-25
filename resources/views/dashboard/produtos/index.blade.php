@extends('dashboard.layout')

@section('title', 'Produtos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Produtos</h1>
    <button class="btn btn-success" style="background:#111827; color:#fff;" onclick="openModal()">Novo Produto</button>
</div>
<div id="productsList" class="row g-4">
    <!-- Os produtos serão inseridos aqui via JavaScript -->
</div>
<div id="noProductsMsg" class="d-none mt-5">
    <li class="list-group-item text-center text-muted py-5" style="font-size:1.2em; border:none; background:transparent; list-style:none;">
        <div style="font-size:2.5em;">📦</div>
        <div class="mt-2">Nenhum produto cadastrado ainda.</div>
    </li>
</div>
<!-- Modal de Cadastro/Edição -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Novo Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="productForm" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="dados-tab" data-bs-toggle="tab" data-bs-target="#dadosProduto" type="button" role="tab" aria-controls="dadosProduto" aria-selected="true">Dados do Produto</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="variacoes-tab" data-bs-toggle="tab" data-bs-target="#variacoesProduto" type="button" role="tab" aria-controls="variacoesProduto" aria-selected="false">Variações</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="estoque-tab" data-bs-toggle="tab" data-bs-target="#estoqueProduto" type="button" role="tab" aria-controls="estoqueProduto" aria-selected="false">Estoque</button>
            </li>
          </ul>
          <div class="tab-content">
            <!-- Aba Dados do Produto -->
            <div class="tab-pane fade show active" id="dadosProduto" role="tabpanel" aria-labelledby="dados-tab">
              <div class="row align-items-start">
                <div class="col-md-5">
                  <div class="mb-3">
                    <label class="form-label">Imagem</label>
                    <input type="file" name="image" accept="image/*" class="form-control" id="imageInput">
                  </div>
                  <div class="w-100 text-center mb-3">
                    <img id="imagePreview" src="https://via.placeholder.com/200x200?text=Prévia" alt="Prévia da imagem" style="max-width: 220px; max-height: 220px; border-radius: 1rem; display: none; margin: 0 auto;" />
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Preço</label>
                    <input type="text" name="price" class="form-control" required maxlength="15" inputmode="decimal" autocomplete="off">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-control" rows="2" maxlength="1000"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <!-- Aba Variações -->
            <div class="tab-pane fade" id="variacoesProduto" role="tabpanel" aria-labelledby="variacoes-tab">
              <div id="variationGroups">
                <!-- Grupos de variações serão inseridos aqui -->
              </div>
              <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addVariationGroup()">Adicionar Grupo de Variação</button>
            </div>
            <!-- Aba Estoque -->
            <div class="tab-pane fade" id="estoqueProduto" role="tabpanel" aria-labelledby="estoque-tab">
              <div id="stockMovementsList" class="mb-3">
                <!-- Lançamentos de estoque serão inseridos aqui via JS -->
              </div>
              <div class="mb-2"><strong>Total em estoque:</strong> <span id="stockTotal">0</span></div>
              <div id="stockMovementForm" class="d-flex gap-2 align-items-end">
                <div>
                  <label class="form-label mb-1">Valor</label>
                  <input type="number" step="1" class="form-control" id="stockValueInput" placeholder="Ex: 10 ou -2">
                </div>
                <div class="mt-2">
                  <label class="form-label mb-1">Observação</label>
                  <input type="text" class="form-control" id="stockNoteInput" placeholder="Opcional">
                </div>
                <button type="button" class="btn btn-outline-success mt-3" id="btnAdicionarEstoque">Adicionar</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="btnSalvarProduto" style="background:#111827; color:#fff;">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    let productModal;
    document.addEventListener('DOMContentLoaded', function() {
        productModal = new bootstrap.Modal(document.getElementById('productModal'));
        loadProducts();
        // Máscara de preço
        const priceInputs = document.querySelectorAll('input[name="price"]');
        priceInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = input.value.replace(/\D/g, '');
                value = (parseInt(value, 10) || 0).toString();
                if (value.length < 3) value = value.padStart(3, '0');
                let reais = value.slice(0, value.length - 2);
                let centavos = value.slice(-2);
                // Adiciona pontos de milhar
                reais = reais.replace(/^0+(?!$)/, '');
                reais = reais.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                input.value = reais + ',' + centavos;
            });
            // Ao focar, seleciona tudo
            input.addEventListener('focus', function() { input.select(); });
        });
        // Preview da imagem
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        imageInput.addEventListener('change', function(e) {
            if (imageInput.files && imageInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imagePreview.src = ev.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(imageInput.files[0]);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
        // Listener para o botão de adicionar estoque
        document.getElementById('btnAdicionarEstoque').addEventListener('click', async function(e) {
            e.preventDefault();
            const valueInput = document.getElementById('stockValueInput');
            const noteInput = document.getElementById('stockNoteInput');
            const value = parseInt(valueInput.value, 10);
            const note = noteInput.value;
            const form = document.getElementById('productForm');
            const productId = form.dataset.id;
            if (!productId) {
                showToast('Salve o produto antes de lançar estoque!', 'error');
                return;
            }
            if (!value || isNaN(value)) {
                showToast('Informe um valor válido para o estoque!', 'error');
                return;
            }
            // Desabilita botão
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Adicionando...';
            try {
                const response = await fetch(`/api/v1/produtos/${productId}/estoque`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ value, note })
                });
                if (response.ok) {
                    valueInput.value = '';
                    noteInput.value = '';
                    showToast('Lançamento de estoque adicionado!', 'success');
                    await carregarMovimentosEstoque(productId);
                } else {
                    const error = await response.json();
                    showToast('Erro ao adicionar estoque: ' + (error.message || JSON.stringify(error)), 'error');
                }
            } catch (err) {
                showToast('Erro ao adicionar estoque!', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Adicionar';
            }
        });
    });

    function parsePriceToFloat(priceStr) {
        if (!priceStr) return 0;
        return parseFloat(priceStr.replace('.', '').replace(',', '.'));
    }

    async function loadProducts() {
        try {
            const response = await fetch('/api/v1/produtos');
            const products = await response.json();
            const productsList = document.getElementById('productsList');
            const noProductsMsg = document.getElementById('noProductsMsg');
            if (products.length === 0) {
                productsList.innerHTML = '';
                noProductsMsg.classList.remove('d-none');
            } else {
                noProductsMsg.classList.add('d-none');
                productsList.innerHTML = products.map(product => `
                    <div class=\"col-md-4 col-lg-3\">
                      <div class=\"card h-100\" style=\"border:1px solid #e5e7eb; border-radius:1rem; background:#fff; box-shadow:none;\">
                        <img src=\"${product.image ? '/' + product.image : 'https://via.placeholder.com/300x200?text=Sem+Imagem'}\" class=\"card-img-top\" alt=\"${product.name}\" style=\"border-radius:1rem 1rem 0 0;\">
                        <div class=\"card-body d-flex flex-column\">
                          <h5 class=\"card-title\" style=\"color:#111827; font-weight:600;\">${product.name}</h5>
                          <p class=\"card-text mb-2\" style=\"color:#6b7280; font-size:0.95em;\">${product.description ? product.description : ''}</p>
                          <p class=\"card-text text-muted mb-2\" style=\"color:#6b7280;\">R$ ${Number(product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                          <div class=\"mt-auto d-flex justify-content-end gap-2\">
                            <button onclick=\"editProduct(${product.id})\" class=\"btn btn-outline-primary btn-sm\" style=\"border-color:#d1d5db; background:#f3f4f6; color:#111827;\">Editar</button>
                            <button onclick=\"deleteProduct(${product.id})\" class=\"btn btn-outline-danger btn-sm\" style=\"border-color:#d1d5db; background:#f3f4f6; color:#b91c1c;\">Excluir</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Erro ao carregar produtos:', error);
        }
    }

    async function saveProduct(formData, id = null) {
        const btnSalvar = document.getElementById('btnSalvarProduto');
        btnSalvar.disabled = true;
        const originalHtml = btnSalvar.innerHTML;
        btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Salvando...';
        // Corrigir o valor do preço para float antes de enviar
        if (formData.get('price')) {
            formData.set('price', parsePriceToFloat(formData.get('price')));
        }
        try {
            const url = id ? `/api/v1/produtos/${id}` : '/api/v1/produtos';
            const method = id ? 'POST' : 'POST';
            const headers = id ? { 'X-HTTP-Method-Override': 'PUT' } : {};
            const response = await fetch(url, {
                method,
                body: formData,
                headers
            });
            if (response.ok) {
                const product = await response.json();
                // Salvar variações após salvar produto
                await saveVariations(product.id);
                productModal.hide();
                loadProducts();
                showToast(id ? 'Produto atualizado com sucesso!' : 'Produto cadastrado com sucesso!', 'success');
            } else {
                const error = await response.json();
                showToast('Erro ao salvar produto: ' + (error.message || JSON.stringify(error)), 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar produto:', error);
        } finally {
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = originalHtml;
        }
    }

    // Função para coletar e salvar variações
    async function saveVariations(productId) {
        const groups = document.querySelectorAll('#variationGroups .card');
        // Remove todas as variações existentes antes de salvar novas (simples)
        await fetch(`/api/v1/produtos/${productId}/variacoes`, { method: 'DELETE' });
        for (const group of groups) {
            const groupName = group.querySelector('input[type="text"]').value.trim();
            const options = group.querySelectorAll('.variation-options input[type="text"]');
            for (const opt of options) {
                const optionName = opt.value.trim();
                if (optionName) {
                    await fetch(`/api/v1/produtos/${productId}/variacoes`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ name: optionName, group: groupName })
                    });
                }
            }
        }
    }

    // Carregar lançamentos ao abrir modal de edição sem sobrescrever editProduct global
    window.editProduct = async function(id) {
        try {
            const response = await fetch(`/api/v1/produtos/${id}`);
            const product = await response.json();
            const form = document.getElementById('productForm');
            form.name.value = product.name;
            form.price.value = Number(product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            form.description.value = product.description || '';
            form.dataset.id = id;
            document.getElementById('modalTitle').textContent = 'Editar Produto';
            // Preview da imagem existente
            const imagePreview = document.getElementById('imagePreview');
            if (product.image) {
                imagePreview.src = '/' + product.image;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.src = 'https://via.placeholder.com/200x200?text=Prévia';
                imagePreview.style.display = 'none';
            }
            // Preencher variações
            const variationGroupsDiv = document.getElementById('variationGroups');
            variationGroupsDiv.innerHTML = '';
            if (product.variations && product.variations.length > 0) {
                // Agrupar por group
                const groupMap = {};
                product.variations.forEach(v => {
                    const group = v.group || '';
                    if (!groupMap[group]) groupMap[group] = [];
                    groupMap[group].push(v.name);
                });
                Object.entries(groupMap).forEach(([group, options]) => {
                    addVariationGroup(group, options);
                });
            }
            productModal.show();
            await carregarMovimentosEstoque(id);
        } catch (error) {
            console.error('Erro ao carregar produto:', error);
        }
    }

    async function deleteProduct(id) {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja realmente excluir este produto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        });
        if (!result.isConfirmed) return;
        try {
            const response = await fetch(`/api/v1/produtos/${id}`, {
                method: 'DELETE'
            });
            if (response.ok) {
                loadProducts();
                showToast('Produto excluído com sucesso!', 'success');
            }
        } catch (error) {
            console.error('Erro ao excluir produto:', error);
        }
    }

    function openModal() {
        document.getElementById('productForm').reset();
        delete document.getElementById('productForm').dataset.id;
        document.getElementById('modalTitle').textContent = 'Novo Produto';
        // Resetar preview da imagem
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = 'https://via.placeholder.com/200x200?text=Prévia';
        imagePreview.style.display = 'none';
        // Limpar variações
        document.getElementById('variationGroups').innerHTML = '';
        // Limpar lançamentos de estoque e total
        document.getElementById('stockMovementsList').innerHTML = '';
        document.getElementById('stockTotal').textContent = '0';
        productModal.show();
    }

    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const id = form.dataset.id;
        await saveProduct(formData, id);
    });

    function showToast(msg, type = 'success') {
        Toastify({
            text: msg,
            duration: 3500,
            close: true,
            gravity: 'top',
            position: 'right',
            style: {
                background: type === 'success' ? '#16a34a' : '#dc2626',
                color: '#fff',
                fontWeight: 600,
            },
        }).showToast();
    }

    // Variações - Frontend
    let variationGroupCount = 0;
    function addVariationGroup(name = '', options = []) {
        variationGroupCount++;
        const groupId = `variationGroup${variationGroupCount}`;
        const groupDiv = document.createElement('div');
        groupDiv.className = 'card mb-3';
        groupDiv.innerHTML = `
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <input type="text" class="form-control form-control-sm me-2" placeholder="Nome do Grupo (ex: Cores)" value="${name}" style="max-width:200px;">
              <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.card').remove()">Remover Grupo</button>
            </div>
            <div class="variation-options mb-2"></div>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="addVariationOption(this)">Adicionar Opção</button>
          </div>
        `;
        document.getElementById('variationGroups').appendChild(groupDiv);
        // Adiciona opções iniciais se houver
        const optionsDiv = groupDiv.querySelector('.variation-options');
        options.forEach(opt => addVariationOption(optionsDiv, opt));
    }
    function addVariationOption(btnOrDiv, value = '') {
        let optionsDiv;
        if (btnOrDiv.parentNode && btnOrDiv.parentNode.querySelector('.variation-options')) {
            optionsDiv = btnOrDiv.parentNode.querySelector('.variation-options');
        } else {
            optionsDiv = btnOrDiv;
        }
        const optDiv = document.createElement('div');
        optDiv.className = 'input-group input-group-sm mb-1';
        optDiv.innerHTML = `
          <input type="text" class="form-control" placeholder="Opção (ex: Azul)" value="${value}">
          <button type="button" class="btn btn-outline-danger" onclick="this.parentNode.remove()">Remover</button>
        `;
        optionsDiv.appendChild(optDiv);
    }

    // Função para carregar lançamentos de estoque e atualizar total
    async function carregarMovimentosEstoque(productId) {
        try {
            const response = await fetch(`/api/v1/produtos/${productId}/estoque`);
            if (!response.ok) return;
            const movimentos = await response.json();
            const lista = document.getElementById('stockMovementsList');
            let total = 0;
            if (movimentos.length === 0) {
                lista.innerHTML = '<div class="text-muted text-center">Nenhum lançamento de estoque.</div>';
            } else {
                lista.innerHTML = '<ul class="list-group mb-2">' + movimentos.map(mov => {
                    total += parseInt(mov.value, 10);
                    return `<li class=\"list-group-item d-flex justify-content-between align-items-center\">`
                        + `<span>${mov.value > 0 ? '+' : ''}${mov.value} <small class=\"text-muted\">${mov.note ? mov.note : ''}</small></span>`
                        + `<button class=\"btn btn-outline-danger btn-sm\" onclick=\"removerMovimentoEstoque(${mov.id})\">Remover</button>`
                        + `</li>`;
                }).join('') + '</ul>';
            }
            document.getElementById('stockTotal').textContent = total;
        } catch (err) {
            document.getElementById('stockMovementsList').innerHTML = '<div class="text-danger">Erro ao carregar lançamentos.</div>';
        }
    }
    // Função global para remover lançamento
    window.removerMovimentoEstoque = async function(id) {
        const form = document.getElementById('productForm');
        const productId = form.dataset.id;
        if (!productId) return;
        const confirm = await Swal.fire({
            title: 'Remover lançamento?',
            text: 'Deseja remover este lançamento de estoque?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, remover',
            cancelButtonText: 'Cancelar'
        });
        if (!confirm.isConfirmed) return;
        try {
            const response = await fetch(`/api/v1/produtos/${productId}/estoque/${id}`, { method: 'DELETE' });
            if (response.ok) {
                showToast('Lançamento removido!', 'success');
                await carregarMovimentosEstoque(productId);
            } else {
                showToast('Erro ao remover lançamento!', 'error');
            }
        } catch (err) {
            showToast('Erro ao remover lançamento!', 'error');
        }
    }
</script>
@endpush 