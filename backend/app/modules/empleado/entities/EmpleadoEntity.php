<?php

class EmpleadoEntity extends Entity
{
    protected static string $table = 'empleados';
    protected static string $primaryKey = 'id_empleado';

    protected static bool $softDelete = true;

    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'idEmpleado'      => 'id_empleado',
        'nombres'         => 'nombres',
        'apellidoPaterno' => 'apellido_paterno',
        'apellidoMaterno' => 'apellido_materno',
        'numDocumento'    => 'num_documento',
        'correo'          => 'correo',
        'telefono'        => 'telefono',
        'estado'          => 'estado'
    ];
}