<?php

namespace App\Http\Controllers;

use App\Domain\UseCases\GetAllClassesUseCase;
use App\Domain\UseCases\CreateClassUseCase;
use App\Domain\UseCases\UpdateClassUseCase;
use App\Domain\UseCases\DeleteClassUseCase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    public function __construct(
        private GetAllClassesUseCase $getAllClassesUseCase,
        private CreateClassUseCase $createClassUseCase,
        private UpdateClassUseCase $updateClassUseCase,
        private DeleteClassUseCase $deleteClassUseCase
    ) {}

    public function index()
    {
        $clases = $this->getAllClassesUseCase->execute();
        return view('secretaria.clases.index', compact('clases'));
    }

    public function create()
    {
        // For view compatibility, we need Eloquent models
        $profesores = \App\Models\Profesor::with('user')->get();
        return view('secretaria.clases.create', compact('profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'profesor_id' => 'required|exists:profesores,id',
            'horario' => 'nullable|string|max:100',
        ]);

        try {
            $this->createClassUseCase->execute(
                $request->nombre,
                $request->codigo,
                $request->profesor_id,
                $request->horario
            );

            return redirect()->route('clases.index')->with('success', 'Class created successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    public function edit(int $clase)
    {
        // For view compatibility, we use Eloquent models
        $clase = \App\Models\Clase::findOrFail($clase);
        $profesores = \App\Models\Profesor::with('user')->get();
        
        return view('secretaria.clases.edit', compact('clase', 'profesores'));
    }

    public function update(Request $request, int $clase)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'profesor_id' => 'required|exists:profesores,id',
            'horario' => 'nullable|string|max:100',
        ]);

        try {
            $this->updateClassUseCase->execute(
                $clase,
                $request->nombre,
                $request->codigo,
                $request->profesor_id,
                $request->horario
            );

            return redirect()->route('clases.index')->with('success', 'Class updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $clase)
    {
        $this->deleteClassUseCase->execute($clase);
        return redirect()->route('clases.index')->with('success', 'Class deleted successfully.');
    }
}
