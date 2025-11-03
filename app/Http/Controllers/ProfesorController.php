<?php

namespace App\Http\Controllers;

use App\Domain\UseCases\GetAllProfessorsUseCase;
use App\Domain\UseCases\CreateProfessorUseCase;
use App\Domain\UseCases\UpdateProfessorUseCase;
use App\Domain\UseCases\DeleteProfessorUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfesorController extends Controller
{
    public function __construct(
        private GetAllProfessorsUseCase $getAllProfessorsUseCase,
        private CreateProfessorUseCase $createProfessorUseCase,
        private UpdateProfessorUseCase $updateProfessorUseCase,
        private DeleteProfessorUseCase $deleteProfessorUseCase
    ) {}

    public function index()
    {
        $profesores = $this->getAllProfessorsUseCase->execute();
        return view('secretaria.profesores.index', compact('profesores'));
    }

    public function create()
    {
        return view('secretaria.profesores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'email'     => 'required|email',
            'cif'       => 'required|string|max:50',
            'facultad'  => 'required|string|max:100',
        ]);

        try {
            $password = Str::random(10); // Secure random password

            $this->createProfessorUseCase->execute(
                $request->nombre,
                $request->apellido,
                $request->email,
                $request->cif,
                $request->facultad,
                $password
            );

            return redirect()->route('profesores.index')->with('success', 'Professor registered successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    public function edit(int $profesor)
    {
        // For view compatibility, we use Eloquent models
        $profesor = \App\Models\Profesor::with('user')->findOrFail($profesor);
        
        return view('secretaria.profesores.edit', compact('profesor'));
    }

    public function update(Request $request, int $profesor)
    {
        $request->validate([
            'apellido' => 'required|string|max:255',
        ]);

        try {
            $this->updateProfessorUseCase->execute($profesor, $request->apellido);

            return redirect()->route('profesores.index')->with('success', 'Professor updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $profesor)
    {
        $this->deleteProfessorUseCase->execute($profesor);
        return redirect()->route('profesores.index')->with('success', 'Professor deleted successfully.');
    }
}
