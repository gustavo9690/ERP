<?php

class EmpleadoEntity extends Entity
{
    protected static string $table = 'empleados';
    protected static string $primaryKey = 'id_empleado';

    protected static bool $softDelete = true;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'id_empleado' => [
            'alias' => 'idEmpleado',
            'type'  => 'int'
        ],
        'nombres' => [
            'alias' => 'nombres',
            'type'  => 'string'
        ],
        'apellido_paterno' => [
            'alias' => 'apellidoPaterno',
            'type'  => 'string'
        ],
        'apellido_materno' => [
            'alias' => 'apellidoMaterno',
            'type'  => 'string'
        ],
        'num_documento' => [
            'alias' => 'numDocumento',
            'type'  => 'string'
        ],
        'correo' => [
            'alias' => 'correo',
            'type'  => 'string'
        ],
        'telefono' => [
            'alias' => 'telefono',
            'type'  => 'string'
        ],
        'estado' => [
            'alias' => 'estado',
            'type'  => 'int'
        ]
    ];
}