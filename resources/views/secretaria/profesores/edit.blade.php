@extends('layouts.secretaria')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Editar Profesor</h2>

    <form method="POST" action="{{ route('profesores.update', $profesor->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block">Nombre</label>
            <input type="text" name="nombre" value="{{ $profesor->user->name }}" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Apellido</label>
            <input type="text" name="apellido" value="{{ $profesor->apellido }}" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" value="{{ $profesor->user->email }}" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Teléfono</label>
            <input type="text" name="telefono" value="{{ $profesor->user->telefono ?? '' }}" class="w-full border p-2">
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection
