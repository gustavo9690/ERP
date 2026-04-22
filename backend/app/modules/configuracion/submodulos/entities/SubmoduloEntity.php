<?php

class SubmoduloEntity extends Entity
{
    protected static string $table = 'submodulos';
    protected static string $primaryKey = 'id_submodulo';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_submodulo' => [
            'alias' => 'idSubmodulo',
            'type'  => 'int'
        ],
        'id_modulo' => [
            'alias' => 'idModulo',
            'type'  => 'int',
            'fk'    => true,
            'embed' => true,
            'relationAlias' => 'modulo',
            'ref'   => [
                'table'  => 'modulos',
                'column' => 'id_modulo',
                'entity' => ModuloEntity::class
            ]
        ],
        'nombre' => [
            'alias' => 'nombreSubmodulo',
            'type'  => 'string'
        ],
        'codigo' => [
            'alias' => 'codigoSubmodulo',
            'type'  => 'string'
        ],
        'ruta' => [
            'alias' => 'ruta',
            'type'  => 'string'
        ],
        'orden' => [
            'alias' => 'orden',
            'type'  => 'int'
        ],
        'estado' => [
            'alias' => 'estadoSubmodulo',
            'type'  => 'bool'
        ],
        
    ];
}

