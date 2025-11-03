@extends('layouts.secretaria')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-6">Panel de Secretaría Académica</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('profesores.index') }}" class="block p-4 bg-blue-100 hover:bg-blue-200 rounded shadow">
            ➤ Gestionar Profesores
        </a>
        <a href="{{ route('clases.index') }}" class="block p-4 bg-green-100 hover:bg-green-200 rounded shadow">
            ➤ Gestionar Clases
        </a>
        <a href="{{ route('solicitudes.index') }}" class="block p-4 bg-yellow-100 hover:bg-yellow-200 rounded shadow">
            ➤ Bandeja de Solicitudes Pendientes
        </a>
    </div>
</div>
@endsection
