<?php

namespace App\Domain\Models;

class Estudiante
{
    private ?int $id;
    private int $userId;
    private string $apellido;
    private string $cif;
    private string $carrera;

    public function __construct(
        int $userId,
        string $apellido,
        string $cif,
        string $carrera,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->apellido = $apellido;
        $this->cif = $cif;
        $this->carrera = $carrera;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getCif(): string
    {
        return $this->cif;
    }

    public function getCarrera(): string
    {
        return $this->carrera;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setCif(string $cif): void
    {
        $this->cif = $cif;
    }

    public function setCarrera(string $carrera): void
    {
        $this->carrera = $carrera;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'apellido' => $this->apellido,
            'cif' => $this->cif,
            'carrera' => $this->carrera,
        ];
    }
}
