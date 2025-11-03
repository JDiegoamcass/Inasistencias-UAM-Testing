<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Profesor;
use Illuminate\Http\Request;

class SecretariaClaseController extends Controller
{
    public function index()
    {
        $clases = Clase::with('profesor.user')->get();
        return view('secretaria.clases.index', compact('clases'));
    }

    public function create()
    {
        $profesores = Profesor::with('user')->get();
        return view('secretaria.clases.create', compact('profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'profesor_id' => 'nullable|exists:profesores,id',
        ]);

        Clase::create($request->only('codigo', 'nombre', 'profesor_id'));

        return redirect()->route('clases.index')->with('success', 'Clase registrada correctamente.');
    }

    public function edit(Clase $clase)
    {
        $profesores = Profesor::with('user')->get();
        return view('secretaria.clases.edit', compact('clase', 'profesores'));
    }

    public function update(Request $request, Clase $clase)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'profesor_id' => 'nullable|exists:profesores,id',
        ]);

        $clase->update($request->only('codigo', 'nombre', 'profesor_id'));

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }
}
