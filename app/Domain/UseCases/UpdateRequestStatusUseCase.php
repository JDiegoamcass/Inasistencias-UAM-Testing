<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RequestRepositoryInterface;

class UpdateRequestStatusUseCase
{
    public function __construct(
        private RequestRepositoryInterface $requestRepository
    ) {}

    public function execute(int $requestId, string $status, ?string $resolution = null): bool
    {
        $request = $this->requestRepository->findById($requestId);

        if (!$request) {
            return false;
        }

        if (!in_array($status, ['aprobado', 'rechazado'])) {
            throw new \InvalidArgumentException('Invalid status. Must be "aprobado" or "rechazado"');
        }

        // Check if transition is allowed
        if ($status === 'aprobado') {
            if (!$request->canApprove()) {
                throw new \RuntimeException('Cannot approve request in current state');
            }
            $request->aprobar($resolution);
        } else {
            if (!$request->canReject()) {
                throw new \RuntimeException('Cannot reject request in current state');
            }
            $request->rechazar($resolution);
        }

        // Observers are automatically notified during approve/reject
        // Now persist the changes
        return $this->requestRepository->update($request);
    }
}
