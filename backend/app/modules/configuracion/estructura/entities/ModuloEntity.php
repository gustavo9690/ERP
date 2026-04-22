<?php

class ModuloEntity extends Entity
{
    protected static string $table = 'modulos';
    protected static string $primaryKey = 'id_modulo';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_modulo' => [
            'alias' => 'idModulo',
            'type'  => 'int'
        ],
        'nombre' => [
            'alias' => 'nombreModulo',
            'type'  => 'string'
        ],
        'codigo' => [
            'alias' => 'codigoModulo',
            'type'  => 'string'
        ],
        'icono' => [
            'alias' => 'icono',
            'type'  => 'string'
        ],
        'orden' => [
            'alias' => 'orden',
            'type'  => 'string'
        ],
        'estado' => [
            'alias' => 'estadoModulo',
            'type'  => 'bool'
        ],
        'fecha_creacion' => [
            'alias' => 'fechaCreacion',
            'type'  => 'datetime'
        ],
        'fecha_modificacion' => [
            'alias' => 'fechaModificacion',
            'type'  => 'datetime'
        ]
    ];
}