<?php

class Usuario extends Entity
{
    protected static string $table = 'usuario';
    protected static string $primaryKey = 'id_usuario';

    protected static bool $softDelete = true;

    protected static string $deleteMode = 'active';

    protected static array $fields = [
        'idUsuario'       => 'id_usuario',
        'usuario'         => 'usuario',
        'clave'           => 'clave',
        'estado'          => 'estado',
        'idEmpleado'      => 'id_empleado'
    ];
}
