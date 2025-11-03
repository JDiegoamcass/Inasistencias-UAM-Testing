
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Secretaría</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-blue-700 text-white p-4">
            <h1 class="text-xl font-bold">Panel de Secretaría</h1>
        </header>

        <main class="flex-grow p-4">
            @yield('content')
        </main>

        <footer class="bg-gray-200 text-center p-4 text-sm text-gray-600">
            © {{ date('Y') }} UAM - Sistema de Inasistencias
        </footer>
    </div>
</body>
</html>
