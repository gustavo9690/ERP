<?php
class PermisosService
{
    private PermisosRepository $repo;

    public function __construct()
    {
        $this->repo = new PermisosRepository();
    }

    public function obtenerJerarquia(int $idOpcion)
    {
        $data = $this->repo->obtenerJerarquia($idOpcion);

        if (!$data) return null;

        return $data;

       /* $opcion = $data['opcion'];
        $submodulo = $data['submodulo'];
        $modulo = $data['modulo'];

        return [
            'modulo' => $modulo['codigoModulo'] ?? null,
            'submodulo' => $submodulo['codigoSubmodulo'] ?? null,
            'opcion' => $opcion['codigoOpcion'] ?? null,
        ];*/
    }
}