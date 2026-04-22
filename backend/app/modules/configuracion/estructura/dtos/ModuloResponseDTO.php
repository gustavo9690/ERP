<?php

class ModuloResponseDTO
{
    public ?int $idModulo = null;
    public string $nombreModulo = '';
    public string $codigoModulo = '';
    public ?string $icono = null;
    public int $orden = 0;
    public int $estadoModulo = 1;

    public static function fromEntity(ModuloEntity $entity): self
    {
        $dto = new self();
        $dto->idModulo = $entity->idModulo;
        $dto->nombreModulo   = $entity->nombreModulo ?? '';
        $dto->codigoModulo   = $entity->codigoModulo ?? '';
        $dto->icono    = $entity->icono;
        $dto->orden    = (int)($entity->orden ?? 0);
        $dto->estadoModulo   = (int)($entity->estadoModulo ?? 1);

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
            'estadoModulo'   => $this->estadoModulo
        ];
    }

    public static function fromEntityList(array $modulos): array
    {
        return array_map(function ($modulo) {
            return self::fromEntity($modulo)->toArray();
        }, $modulos);
    }
}