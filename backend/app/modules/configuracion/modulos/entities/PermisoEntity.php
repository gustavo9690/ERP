<?php

class PermisoEntity extends Entity
{
    protected static string $table = 'permisos';
    protected static string $primaryKey = 'id_permiso';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_permiso' => [
            'alias' => 'idPermiso',
            'type'  => 'int'
        ],
        'id_opcion' => [
            'alias' => 'idOpcion',
            'type'  => 'int',
            'fk'    => true,
            'embed' => true,
            'relationAlias' => 'opciones',
            'ref'   => [
                'table'  => 'opciones',
                'column' => 'id_opcion',
                'entity' => OpcionEntity::class
            ]
        ],
        'id_accion' => [
            'alias' => 'idAccion',
            'type'  => 'int',
            'fk'    => true,
            'embed' => true,
            'relationAlias' => 'acciones',
            'ref'   => [
                'table'  => 'acciones',
                'column' => 'id_accion',
                'entity' => AccionEntity::class
            ]
        ],
        'permitido' => [
            'alias' => 'permitido',
            'type'  => 'bool'
        ],
        
    ];
}