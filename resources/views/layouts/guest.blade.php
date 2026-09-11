<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sign In') - {{ config('app.name', 'JARA') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --jara-primary: #4f46e5;
            --jara-primary-hover: #4338ca;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.35);
        }

        .btn-jara {
            background-color: var(--jara-primary);
            color: #ffffff;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.2s;
        }

        .btn-jara:hover {
            background-color: var(--jara-primary-hover);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .form-control {
            border-radius: 10px;
            padding: 0.7rem 1rem;
            border-color: #cbd5e1;
        }

        .form-control:focus {
            border-color: var(--jara-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="brand-logo mb-2">
                    <i class="bi bi-kanban-fill"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0">JARA</h3>
                <p class="text-muted small">Job Activity & Responsibility Assistant</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 py-2 small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 py-2 small" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
