<?php
class ModulosController extends Controller
{
    private ModulosService $service;

    public function __construct()
    {
        $this->service = new ModulosService();
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