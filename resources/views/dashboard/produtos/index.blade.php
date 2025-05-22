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
<div id="noProductsMsg" class="alert alert-info text-center d-none mt-5">Nenhum produto cadastrado ainda.</div>
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" style="background:#111827; color:#fff;">Salvar</button>
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
                        <img src=\"${product.image ? '/storage/' + product.image : 'https://via.placeholder.com/300x200?text=Sem+Imagem'}\" class=\"card-img-top\" alt=\"${product.name}\" style=\"border-radius:1rem 1rem 0 0;\">
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
                productModal.hide();
                loadProducts();
                showToast(id ? 'Produto atualizado com sucesso!' : 'Produto cadastrado com sucesso!', 'success');
            } else {
                const error = await response.json();
                showToast('Erro ao salvar produto: ' + (error.message || JSON.stringify(error)), 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar produto:', error);
        }
    }

    async function editProduct(id) {
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
                imagePreview.src = '/storage/' + product.image;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.src = 'https://via.placeholder.com/200x200?text=Prévia';
                imagePreview.style.display = 'none';
            }
            productModal.show();
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
</script>
@endpush 