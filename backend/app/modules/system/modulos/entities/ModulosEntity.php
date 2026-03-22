<?php

class ModuleEntity extends Entity
{

    protected static string $table = 'modules';

    protected static string $primaryKey = 'id_module';

    protected static array $fields = [

        'idModule' => 'id_module',
        'nombre' => 'nombre',
        'estado' => 'estado',
        'version' => 'version',
        'instaladoEn' => 'instalado_en'

    ];

}