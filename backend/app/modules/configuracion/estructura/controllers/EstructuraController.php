<?php
class EstructuraController extends Controller
{
    private EstructuraService $service;

    public function __construct()
    {
        $this->service = new EstructuraService();
    }

    public function listar_modulos(): void
    {
        try {
            $data = $this->service->listarModulos();
            Response::success($data, 'Módulos obtenidos correctamente');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}