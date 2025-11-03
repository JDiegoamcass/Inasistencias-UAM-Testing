@extends('layouts.secretaria')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Bienvenida, Secretaría</h2>

    <div class="grid gap-4">
        <a href="{{ route('profesores.index') }}" class="block p-4 bg-white rounded shadow hover:bg-blue-50">
            👨‍🏫 Gestionar Profesores
        </a>
        <a href="{{ route('clases.index') }}" class="block p-4 bg-white rounded shadow hover:bg-blue-50">
            📚 Gestionar Clases
        </a>
        <a href="{{ route('solicitudes.index') }}" class="block p-4 bg-white rounded shadow hover:bg-blue-50">
            🔔 Ver Solicitudes Pendientes
        </a>
    </div>
@endsection
