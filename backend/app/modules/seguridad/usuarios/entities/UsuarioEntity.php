<?php

class UsuarioEntity extends Entity
{
    protected static string $table = 'usuarios';
    protected static string $primaryKey = 'id_usuario';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_usuario' => [
            'alias' => 'idUsuario',
            'type'  => 'int'
        ],
        'usuario' => [
            'alias' => 'usuario',
            'type'  => 'string'
        ],
        'clave' => [
            'alias' => 'clave',
            'type'  => 'string'
        ],
        'estado' => [
            'alias' => 'estado',
            'type'  => 'int'
        ],
        'id_empleado' => [
            'alias' => 'idEmpleado',
            'type'  => 'int',
            'fk'    => true,
            'embed' => true,
            'relationAlias' => 'empleado',
            'ref'   => [
                'table'  => 'empleados',
                'column' => 'id_empleado',
                'entity' => EmpleadoEntity::class
            ]
        ]
    ];
}