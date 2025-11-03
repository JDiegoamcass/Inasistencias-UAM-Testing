<?php

namespace App\Http\Controllers;

use App\Domain\UseCases\GetPendingRequestsUseCase;

class SecretariaSolicitudController extends Controller
{
    public function __construct(
        private GetPendingRequestsUseCase $getPendingRequestsUseCase
    ) {}

    public function index()
    {
        $solicitudes = $this->getPendingRequestsUseCase->execute();
        return view('secretaria.solicitudes.index', compact('solicitudes'));
    }
}
