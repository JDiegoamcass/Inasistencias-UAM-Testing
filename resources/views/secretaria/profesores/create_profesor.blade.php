@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Crear Profesor</h2>
    <form method="POST" action="{{ route('profesores.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block">Nombre</label>
            <input type="text" name="name" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Apellido</label>
            <input type="text" name="apellido" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Contraseña</label>
            <input type="password" name="password" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">CIF</label>
            <input type="text" name="cif" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Carrera</label>
            <input type="text" name="carrera" class="w-full border p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2">Crear</button>
    </form>
</div>
@endsection
