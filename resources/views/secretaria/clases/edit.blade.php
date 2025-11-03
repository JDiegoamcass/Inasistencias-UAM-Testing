@extends('layouts.secretaria')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Editar Clase</h2>

    <form method="POST" action="{{ route('clases.update', $clase->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block">Código</label>
            <input type="text" name="codigo" value="{{ $clase->codigo }}" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Nombre</label>
            <input type="text" name="nombre" value="{{ $clase->nombre }}" class="w-full border p-2" required>
        </div>
        <div class="mb-4">
            <label class="block">Profesor</label>
            <select name="profesor_id" class="w-full border p-2">
                @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->id }}" {{ $profesor->id == $clase->profesor_id ? 'selected' : '' }}>
                        {{ $profesor->user->name }} {{ $profesor->apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection
