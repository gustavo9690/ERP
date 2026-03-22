<?php

class RolesRequestDTO
{
    public ?int $idRol;
    public string $nombre;
    public ?string $descripcion;
    public ?bool $estado;

    public static function fromRequest(): self
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $dto = new self();

        $dto->idRol = $data['id_rol'] ?? null;
        $dto->nombre = $data['nombre'] ?? '';
        $dto->descripcion = $data['descripcion'] ?? null;
        $dto->estado = $data['estado'] ?? null;

        return $dto;
    }
}