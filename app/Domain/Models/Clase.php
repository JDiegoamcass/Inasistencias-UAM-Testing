<?php

namespace App\Domain\Models;

class Clase
{
    private ?int $id;
    private string $nombre;
    private string $codigo;
    private int $profesorId;
    private ?string $horario;

    public function __construct(
        string $nombre,
        string $codigo,
        int $profesorId,
        ?string $horario = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->profesorId = $profesorId;
        $this->horario = $horario;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getProfesorId(): int
    {
        return $this->profesorId;
    }

    public function getHorario(): ?string
    {
        return $this->horario;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setProfesorId(int $profesorId): void
    {
        $this->profesorId = $profesorId;
    }

    public function setHorario(?string $horario): void
    {
        $this->horario = $horario;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'profesor_id' => $this->profesorId,
            'horario' => $this->horario,
        ];
    }
}
