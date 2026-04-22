<?php

class OpcionEntity extends Entity
{
    protected static string $table = 'opciones';
    protected static string $primaryKey = 'id_opcion';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_opcion' => [
            'alias' => 'idOpcion',
            'type'  => 'int'
        ],
        'id_submodulo' => [
            'alias' => 'idSubmodulo',
            'type'  => 'int',
            'fk'    => true,
            'embed' => true,
            'relationAlias' => 'submodulo',
            'ref'   => [
                'table'  => 'submodulos',
                'column' => 'id_submodulo',
                'entity' => SubmoduloEntity::class
            ]
        ],
        'nombre' => [
            'alias' => 'nombreOpcion',
            'type'  => 'string'
        ],
        'codigo' => [
            'alias' => 'codigoOpcion',
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
            'alias' => 'estadoOpcion',
            'type'  => 'bool'
        ],
        
    ];
}

