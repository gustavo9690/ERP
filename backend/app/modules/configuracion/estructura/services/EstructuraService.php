<?php
class EstructuraService
{
    private EstructuraRepository $repo;

    public function __construct()
    {
        $this->repo = new EstructuraRepository();
    }

    public function listarModulos()
    {
        $data = $this->repo->obtenerModulos();

        return ModuloResponseDTO::fromEntityList($data);
    }

    public function obtener_opciones(int $idOpcion)
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