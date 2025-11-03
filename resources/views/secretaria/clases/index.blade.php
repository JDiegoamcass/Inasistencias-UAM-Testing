@extends('layouts.secretaria')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Listado de Clases</h2>

    <a href="{{ route('clases.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        ➕ Nueva Clase
    </a>

    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Código</th>
                <th class="border p-2">Nombre</th>
                <th class="border p-2">Profesor</th>
                <th class="border p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clases as $clase)
                <tr>
                    <td class="border p-2">{{ $clase->codigo }}</td>
                    <td class="border p-2">{{ $clase->nombre }}</td>
                    <td class="border p-2">{{ $clase->profesor->user->name ?? 'Sin asignar' }}</td>
                    <td class="border p-2">
                        <a href="#" class="text-blue-600">Editar</a> |
                        <a href="#" class="text-red-600">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
