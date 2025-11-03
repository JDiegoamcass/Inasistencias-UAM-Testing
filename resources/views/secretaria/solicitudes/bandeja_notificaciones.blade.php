@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Solicitudes Pendientes</h2>

    @if($solicitudes->isEmpty())
        <p class="text-gray-600">No hay solicitudes pendientes.</p>
    @else
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Estudiante</th>
                    <th class="border p-2">Fecha de Solicitud</th>
                    <th class="border p-2">Fecha de Ausencia</th>
                    <th class="border p-2">Comentario</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                    <tr>
                        <td class="border p-2">{{ $solicitud->user->name }} {{ $solicitud->user->apellido }}</td>
                        <td class="border p-2">{{ $solicitud->fechaSolicitud }}</td>
                        <td class="border p-2">{{ $solicitud->fechaAusencia }}</td>
                        <td class="border p-2">{{ $solicitud->comentario }}</td>
                        <td class="border p-2 text-center">
                            <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="aprobado">
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 text-sm rounded">Aprobar</button>
                            </form>
                            <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="rechazado">
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 text-sm rounded">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
