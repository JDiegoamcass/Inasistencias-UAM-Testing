@extends('layouts.secretaria')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Registrar Clase</h2>

    <form method="POST" action="{{ route('clases.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block">Código</label>
            <input type="text" name="codigo" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Nombre</label>
            <input type="text" name="nombre" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Profesor</label>
            <select name="profesor_id" class="w-full border p-2">
                @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->id }}">{{ $profesor->user->name }} {{ $profesor->apellido }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection
