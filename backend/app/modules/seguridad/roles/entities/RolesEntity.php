<?php

class RolesEntity extends Entity
{
    protected static string $table = 'roles';
    protected static string $primaryKey = 'id_rol';

    protected static bool $softDelete = true;

    protected static string $deleteMode = 'with';

    protected static array $fields = [
        'idRol'           => 'id_rol',
        'nombre'          => 'nombre',
        'descripcion'     => 'descripcion',
        'estado'          => 'estado'
    ];
}