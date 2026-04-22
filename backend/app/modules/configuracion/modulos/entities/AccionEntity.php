<?php

class AccionEntity extends Entity
{
    protected static string $table = 'acciones';
    protected static string $primaryKey = 'id_accion';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_accion' => [
            'alias' => 'idAccion',
            'type'  => 'int'
        ],
        'nombre' => [
            'alias' => 'nombreOpcion',
            'type'  => 'string'
        ],
        'codigo' => [
            'alias' => 'codigoOpcion',
            'type'  => 'string'
        ],
        'estado' => [
            'alias' => 'estadoAccion',
            'type'  => 'bool'
        ],
        
    ];
}

