<?php

namespace App\Domain\Models;

class Profesor
{
    private ?int $id;
    private int $userId;
    private string $apellido;
    private string $cif;
    private string $facultad;

    public function __construct(
        int $userId,
        string $apellido,
        string $cif,
        string $facultad,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->apellido = $apellido;
        $this->cif = $cif;
        $this->facultad = $facultad;
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

    public function getFacultad(): string
    {
        return $this->facultad;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setCif(string $cif): void
    {
        $this->cif = $cif;
    }

    public function setFacultad(string $facultad): void
    {
        $this->facultad = $facultad;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'apellido' => $this->apellido,
            'cif' => $this->cif,
            'facultad' => $this->facultad,
        ];
    }
}
