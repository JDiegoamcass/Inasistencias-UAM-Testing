<?php

namespace App\Http\Controllers;

use App\Domain\UseCases\GetPendingRequestsUseCase;
use App\Domain\UseCases\UpdateRequestStatusUseCase;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function __construct(
        private GetPendingRequestsUseCase $getPendingRequestsUseCase,
        private UpdateRequestStatusUseCase $updateRequestStatusUseCase
    ) {}

    public function index()
    {
        $solicitudes = $this->getPendingRequestsUseCase->execute();
        return view('secretaria.solicitudes.index', compact('solicitudes'));
    }

    public function update(Request $request, int $solicitud)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
        ]);

        try {
            $this->updateRequestStatusUseCase->execute(
                $solicitud,
                $request->estado,
                $request->resolucion ?? null
            );

            return redirect()->route('solicitudes.index')
                             ->with('success', 'Request ' . $request->estado . ' successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('solicitudes.index')
                             ->with('error', $e->getMessage());
        }
    }
}
