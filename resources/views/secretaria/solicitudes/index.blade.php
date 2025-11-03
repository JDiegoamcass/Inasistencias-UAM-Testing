@extends('layouts.secretaria')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Solicitudes de Inasistencia</h2>

    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Estudiante</th>
                <th class="border p-2">Fecha de Solicitud</th>
                <th class="border p-2">Estado</th>
                <th class="border p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($solicitudes as $solicitud)
                <tr>
                    <td class="border p-2">{{ $solicitud->user->name }}</td>
                    <td class="border p-2">{{ $solicitud->fechaSolicitud }}</td>
                    <td class="border p-2">{{ $solicitud->estado }}</td>
                    <td class="border p-2">
                        <form method="POST" action="{{ route('solicitudes.update', $solicitud->id) }}" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="estado" value="aprobado">
                            <button class="text-green-600">Aprobar</button>
                        </form>
                        <form method="POST" action="{{ route('solicitudes.update', $solicitud->id) }}" class="inline ml-2">
                            @csrf @method('PUT')
                            <input type="hidden" name="estado" value="rechazado">
                            <button class="text-red-600">Rechazar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
