<?php

class PermisosRepository extends Repository
{
    protected string $entityClass = PermisoEntity::class;

    protected string $accionEntity = AccionEntity::class;
    protected string $moduloEntity = ModuloEntity::class;
    protected string $opcionEntity = OpcionEntity::class;
    protected string $submoduloEntity = SubmoduloEntity::class;
    protected string $permisoEntity = PermisoEntity::class;

    public function obtenerJerarquia(int $idOpcion): ?array
    {
        $opcion = $this->findByEntity(
            $this->opcionEntity,
            'findByIdOpcion',
            [$idOpcion]
        );

        if (!$opcion) return null;

        $submodulo = $opcion->idSubmodulo ?? null;
        if (!$submodulo) return null;

        $modulo = $submodulo['idModulo'] ?? null;
        if (!$modulo) return null;

        return [
            'modulo' => $modulo,
            'submodulo' => $submodulo,
            'opcion' => $opcion->toArray()
        ];
    }
}