@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Crear Clase</h2>
    <form method="POST" action="{{ route('clases.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block">Nombre de la Clase</label>
            <input type="text" name="nombre" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Código</label>
            <input type="text" name="codigo" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Horario</label>
            <input type="text" name="horario" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label class="block">Profesor Asignado</label>
            <select name="profesor_id" class="w-full border p-2" required>
                @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->id }}">{{ $profesor->user->name }} {{ $profesor->apellido }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2">Crear Clase</button>
    </form>
</div>
@endsection
