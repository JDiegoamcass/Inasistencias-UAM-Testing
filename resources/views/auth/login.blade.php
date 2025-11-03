<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="icon" href="{{ asset('images/uam-logo.png') }}" type="image/x-icon">
    <meta name="description" content="Inicio de sesión en el sistema de inasistencias de la Universidad Americana.">
    <meta name="keywords" content="UAM, inasistencias, universidad, alumnos, profesores, gestión">
    <meta name="author" content="Scrum masters">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        uam: '#009DA9', // fondo base
                    }
                }
            }
        }
    </script>

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-uam min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm text-center">

        <!-- Logo -->
        <div class="bg-white p-4 rounded-xl mb-8">
            <img src="{{ asset('images/uam-logo.png') }}" alt="Logo UAM" class="mx-auto w-32 h-32 object-contain">
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- CIF -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white">
                    <i class="fas fa-user"></i>
                </span>
                <input
                    type="text"
                    name="email"
                    placeholder="CIF"
                    class="pl-10 w-full py-2 rounded border border-white bg-transparent text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white"
                    required
                >
            </div>

            <!-- PIN -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    name="password"
                    placeholder="PIN"
                    class="pl-10 w-full py-2 rounded border border-white bg-transparent text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white"
                    required
                >
            </div>

            <!-- Botón -->
            <button type="submit" class="w-full bg-white text-uam font-semibold py-2 rounded hover:bg-gray-100 hover:scale-105 transition">
                INICIAR SESIÓN
            </button>
        </form>

        <!-- Enlaces -->
        <div class="flex justify-between mt-4 text-sm text-white">
            <a href="{{ route('register') }}" class="hover:underline">Crear cuenta</a>
            <a href="{{ route('password.request') }}" class="hover:underline">¿Olvidaste tu PIN?</a>
        </div>
    </div>

</body>
</html>
