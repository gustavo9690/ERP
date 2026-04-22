<?php
class PermisosController extends Controller
{
    private PermisosService $service;

    public function __construct()
    {
        $this->service = new PermisosService();
    }

    public function prueba()
    {
        try {
            $data = $this->service->obtenerJerarquia(1);
            Response::success($data, 'OK');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}