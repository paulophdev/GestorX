@extends('dashboard.layout')

@section('title', 'Cupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Cupons</h1>
    <button class="btn btn-success" style="background:#111827; color:#fff;" onclick="openCouponModal()">Novo Cupom</button>
</div>
<ul id="couponsList" class="list-group mb-4">
    <!-- Os cupons serão inseridos aqui via JavaScript -->
</ul>

<!-- Modal de Cadastro/Edição de Cupom -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="couponModalTitle">Novo Cupom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="couponForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="code" class="form-control" required maxlength="30">
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo de Desconto</label>
            <select name="discount_type" class="form-select" required>
              <option value="percent">Percentual (%)</option>
              <option value="fixed">Valor Fixo (R$)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Valor do Desconto</label>
            <input type="number" name="discount_value" class="form-control" required min="0" step="0.01">
          </div>
          <div class="mb-3">
            <label class="form-label">Data de Expiração</label>
            <input type="date" name="expires_at" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Limite de Uso</label>
            <input type="number" name="usage_limit" class="form-control" min="1">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    let couponModal;
    document.addEventListener('DOMContentLoaded', function() {
        couponModal = new bootstrap.Modal(document.getElementById('couponModal'));
        loadCoupons();
        // Máscara dinâmica para valor do desconto
        const form = document.getElementById('couponForm');
        const discountType = form.elements['discount_type'];
        const discountValue = form.elements['discount_value'];

        function setDiscountMask() {
            // discountValue.value = ''; // Removido para não apagar valor ao editar
            discountValue.removeAttribute('max');
            discountValue.removeAttribute('min');
            discountValue.removeAttribute('step');
            discountValue.type = 'text';
            discountValue.placeholder = '';
            discountValue.classList.remove('is-invalid');
            discountValue.readOnly = false;
            discountValue.oninput = null;
            if (discountType.value === 'fixed') {
                discountValue.placeholder = 'R$ 0,00';
                discountValue.maxLength = 15;
                discountValue.oninput = function(e) {
                    let v = e.target.value.replace(/\D/g, '');
                    v = (parseInt(v, 10) || 0).toString();
                    if (v.length < 3) v = v.padStart(3, '0');
                    let reais = v.slice(0, v.length - 2);
                    let centavos = v.slice(-2);
                    reais = reais.replace(/^0+(?!$)/, '');
                    reais = reais.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    e.target.value = 'R$ ' + reais + ',' + centavos;
                };
            } else {
                discountValue.placeholder = '0';
                discountValue.maxLength = 3;
                discountValue.oninput = function(e) {
                    let val = e.target.value.replace(/\D/g, '');
                    if (val.length > 3) val = val.slice(0, 3);
                    let num = parseInt(val, 10);
                    if (isNaN(num)) num = '';
                    else if (num > 100) num = 100;
                    e.target.value = num;
                };
            }
        }
        discountType.addEventListener('change', setDiscountMask);
        setDiscountMask();
    });

    async function loadCoupons() {
        const response = await fetch('/api/v1/cupons');
        const coupons = await response.json();
        const list = document.getElementById('couponsList');
        if (coupons.length === 0) {
            list.innerHTML = '<li class="list-group-item text-center text-muted py-5" style="font-size:1.2em; border:none; background:transparent;">'
                + '<div style="font-size:2.5em;">🎟️</div>'
                + '<div class="mt-2">Nenhum cupom cadastrado ainda.</div>'
                + '</li>';
        } else {
            list.innerHTML = coupons.map(coupon => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        O Cupom <span class="fw-bold">${coupon.code}</span> de 
                        <span class="fw-bold">${coupon.discount_type === 'percent' ? coupon.discount_value + '%' : 'R$ ' + Number(coupon.discount_value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span>
                        expira em <span>${coupon.expires_at ? (() => { const [y, m, d] = coupon.expires_at.split('-'); return `${d}/${m}/${y}`; })() : 'sem data'}</span>
                        e já foi utilizado <span>${coupon.used_count ?? 0}/${coupon.usage_limit ?? '∞'}</span>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="editCoupon(${coupon.id})">Editar</button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteCoupon(${coupon.id})">Excluir</button>
                    </div>
                </li>
            `).join('');
        }
    }

    function openCouponModal() {
        document.getElementById('couponForm').reset();
        delete document.getElementById('couponForm').dataset.id;
        document.getElementById('couponModalTitle').textContent = 'Novo Cupom';
        couponModal.show();
    }

    document.getElementById('couponForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const id = form.dataset.id;

        // TRATAMENTO DO VALOR DO DESCONTO
        let discountType = form.discount_type.value;
        let discountValue = form.discount_value.value;
        if (discountType === 'fixed') {
            discountValue = discountValue.replace(/[^\d,]/g, '').replace(',', '.');
            formData.set('discount_value', parseFloat(discountValue));
        } else {
            formData.set('discount_value', parseInt(discountValue));
        }

        let url = '/api/v1/cupons';
        let method = 'POST';
        let headers = { 'Accept': 'application/json' };
        if (id) {
            url += '/' + id;
            headers['X-HTTP-Method-Override'] = 'PUT';
        }
        const response = await fetch(url, {
            method,
            body: formData,
            headers
        });
        if (response.ok) {
            couponModal.hide();
            loadCoupons();
            showToast(id ? 'Cupom atualizado com sucesso!' : 'Cupom cadastrado com sucesso!', 'success');
        } else {
            const error = await response.json();
            showToast('Erro ao salvar cupom: ' + (error.message || JSON.stringify(error)), 'error');
        }
    });

    async function editCoupon(id) {
        const response = await fetch(`/api/v1/cupons/${id}`);
        const coupon = await response.json();
        const form = document.getElementById('couponForm');
        form.code.value = coupon.code;
        form.discount_type.value = coupon.discount_type;

        // Aplica a máscara correta ao valor
        if (coupon.discount_type === 'fixed') {
            form.discount_value.value = 'R$ ' + Number(coupon.discount_value).toLocaleString('pt-BR', {minimumFractionDigits: 2});
        } else {
            form.discount_value.value = coupon.discount_value;
        }

        form.expires_at.value = coupon.expires_at ?? '';
        form.usage_limit.value = coupon.usage_limit ?? '';
        form.dataset.id = id;
        document.getElementById('couponModalTitle').textContent = 'Editar Cupom';

        // Força a máscara correta ao abrir o modal
        const event = new Event('change');
        form.discount_type.dispatchEvent(event);

        couponModal.show();
    }

    async function deleteCoupon(id) {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja realmente excluir este cupom?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        });
        if (!result.isConfirmed) return;
        const response = await fetch(`/api/v1/cupons/${id}`, { method: 'DELETE' });
        if (response.ok) {
            loadCoupons();
            showToast('Cupom excluído com sucesso!', 'success');
        }
    }

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