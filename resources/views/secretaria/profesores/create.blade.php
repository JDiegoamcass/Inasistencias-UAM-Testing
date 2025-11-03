@extends('layouts.secretaria')

@section('content')
    <h2 class="text-xl font-bold mb-4">Registrar nuevo profesor</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profesores.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nombre" class="block font-semibold">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="apellido" class="block font-semibold">Apellido:</label>
            <input type="text" name="apellido" id="apellido" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="email" class="block font-semibold">Correo electrónico:</label>
            <input type="email" name="email" id="email" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="cif" class="block font-semibold">CIF:</label>
            <input type="text" name="cif" id="cif" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="facultad" class="block font-semibold">Facultad:</label>
            <input type="text" name="facultad" id="facultad" required class="w-full border rounded p-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Registrar</button>
    </form>
@endsection
