<?php

class ModuloResponseDTO
{
    public ?int $idModulo = null;
    public string $nombreModulo = '';
    public string $codigoModulo = '';
    public ?string $icono = null;
    public int $orden = 0;
    public int $estadoModulo = 1;
    public ?Datetime $fechaCreacion = null;
    public ?Datetime $fechaModificacion = null;

    public static function fromEntity(ModuloEntity $entity): self
    {
        $dto = new self();
        $dto->idModulo = $entity->idModulo;
        $dto->nombreModulo   = $entity->nombreModulo ?? '';
        $dto->codigoModulo   = $entity->codigoModulo ?? '';
        $dto->icono    = $entity->icono;
        $dto->orden    = (int)($entity->orden ?? 0);
        $dto->estadoModulo   = (int)($entity->estadoModulo ?? 1);
        $dto->fechaCreacion = $entity->fechaCreacion;
        $dto->fechaModificacion = $entity->fechaModificacion;


        return $dto;
    }

    public function toArray(): array
    {
        return [
            'idModulo' => $this->idModulo,
            'nombreModulo'   => $this->nombreModulo,
            'codigoModulo'   => $this->codigoModulo,
            'icono'    => $this->icono,
            'orden'    => $this->orden,
            'estadoModulo'   => $this->estadoModulo,
            'fechaCreacion' => $this->fechaCreacion ? $this->fechaCreacion->format('Y-m-d H:i:s') : null,
            'fechaModificacion' => $this->fechaModificacion ? $this->fechaModificacion->format('Y-m-d H:i:s') : null
        ];
    }

    public static function fromEntityList(array $modulos): array
    {
        return array_map(function ($modulo) {
            return self::fromEntity($modulo)->toArray();
        }, $modulos);
    }
}