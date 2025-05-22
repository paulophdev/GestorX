<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GestorX')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f3f4f6; font-family: 'Open Sans', Arial, sans-serif; }
        .navbar { background: #111 !important; }
        .navbar .navbar-brand { color: #fff !important; font-family: 'Open Sans', Arial, sans-serif; }
        .btn-primary, .btn-success { border: none; }
        .card, .modal-content { border-radius: 1rem; border: 1px solid #e5e7eb; background: #fff; box-shadow: none !important; font-family: 'Open Sans', Arial, sans-serif; }
        .card-title { color: #111827; font-weight: 600; font-family: 'Open Sans', Arial, sans-serif; }
        .card-text { color: #6b7280; }
        .btn-outline-primary { color: #111827; border-color: #d1d5db; background: #f3f4f6; }
        .btn-outline-primary:hover { background: #111827; color: #fff; border-color: #111827; }
        .btn-outline-danger { color: #b91c1c; border-color: #d1d5db; background: #f3f4f6; }
        .btn-outline-danger:hover { background: #b91c1c; color: #fff; border-color: #b91c1c; }
        .alert-info { background: #f3f4f6; color: #111827; border: 1px solid #d1d5db; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/dashboard/pedidos">GestorX</a>
            <div class="d-flex gap-3">
                <a class="nav-link text-white fw-semibold" href="/dashboard/pedidos">Pedidos</a>
                <a class="nav-link text-white fw-semibold" href="/dashboard/produtos">Produtos</a>
                <a class="nav-link text-white fw-semibold" href="/dashboard/cupons">Cupons</a>
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html> 