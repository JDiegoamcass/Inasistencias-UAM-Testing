
<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecretariaProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::with('user')->get();
        return view('secretaria.profesores.index', compact('profesores'));
    }

    public function create()
    {
        return view('secretaria.profesores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cif' => 'required|string|max:100',
            'facultad' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make('12345678'),
            'role' => 'profesor',
        ]);

        Profesor::create([
            'user_id' => $user->id,
            'apellido' => $request->apellido,
            'cif' => $request->cif,
            'facultad' => $request->facultad,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor registrado exitosamente.');
    }

    public function edit(Profesor $profesor)
    {
        return view('secretaria.profesores.edit', compact('profesor'));
    }

    public function update(Request $request, Profesor $profesor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $profesor->user_id,
            'cif' => 'required|string|max:100',
            'facultad' => 'required|string|max:255',
        ]);

        $profesor->user->update([
            'name' => $request->nombre,
            'email' => $request->email,
        ]);

        $profesor->update([
            'apellido' => $request->apellido,
            'cif' => $request->cif,
            'facultad' => $request->facultad,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente.');
    }

    public function destroy(Profesor $profesor)
    {
        $profesor->user->delete();
        $profesor->delete();

        return redirect()->route('profesores.index')->with('success', 'Profesor eliminado correctamente.');
    }
}
