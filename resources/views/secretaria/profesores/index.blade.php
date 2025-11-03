@extends('layouts.secretaria')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Listado de Profesores</h2>

    <a href="{{ route('profesores.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        ➕ Nuevo Profesor
    </a>

    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nombre</th>
                <th class="border p-2">Apellido</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profesores as $profesor)
                <tr>
                    <td class="border p-2">{{ $profesor->user->name }}</td>
                    <td class="border p-2">{{ $profesor->apellido }}</td>
                    <td class="border p-2">{{ $profesor->user->email }}</td>
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
