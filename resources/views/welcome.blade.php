<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inasistencias - UAM</title>
    <link rel="icon" href="{{ asset('images/uam-logo.png') }}" type="image/x-icon">
    <meta name="description" content="Sistema de gestión de inasistencias para la Universidad Americana. Plataforma para alumnos y profesores.">
    <meta name="keywords" content="UAM, inasistencias, universidad, alumnos, profesores, gestión">
    <meta name="author" content="Scrum masters">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative bg-[#0E7490] min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-xl shadow-xl text-center max-w-md w-full">
        <!-- Logo UAM -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/uam-logo.png') }}" alt="Logo UAM" class="w-24 h-24 object-contain">
        </div>

        <!-- Título -->
        <h1 class="text-2xl font-bold text-[#0E7490] mb-2">¡Bienvenido al sistema de inasistencias!</h1>
        <p class="text-gray-600 mb-6">Seleccione al sitio al que desea ingresar</p>

        <!-- Botones -->
        <div class="flex flex-col gap-4">
            <a href="/login" class="bg-cyan-700 text-white py-2 rounded-lg font-semibold hover:bg-cyan-800 transition">Alumnos</a>
            <a href="#" class="bg-cyan-700 text-white py-2 rounded-lg font-semibold hover:bg-cyan-800 transition">Profesores</a>
        </div>
    </div>

</body>
</html>
