<?php

class UsuariosController extends Controller
{
    private UsuarioService $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function obtener(): void
    {
        try {
            $usuarios = $this->service->obtenerTodos();
            Response::success($usuarios, 'Usuarios obtenidos correctamente');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}