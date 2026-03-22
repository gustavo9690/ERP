<?php

class UsuarioService
{
    private UsuarioRepository $repository;

    public function __construct()
    {
        $this->repository = new UsuarioRepository();
    }

    public function obtenerTodos(): array
    {
        $usuarios = $this->repository->findAll();

        return array_map(function ($usuario) {
            return (new UsuarioDTO($usuario->toArray()))->toResponse();
        }, $usuarios);
    }

    public function obtenerPorId(int $id): ?UsuarioEntity
    {
        return $this->repository->findById($id);
    }

    public function buscarPorUsuarioYEstado(string $usuario, int $estado)
    {
        return $this->repository->findByUsuarioAndEstado($usuario, $estado);
    }

    public function guardar(UsuarioEntity $entity): bool
    {
        return $this->repository->save($entity);
    }

    public function eliminar(int $id): bool
    {
        return $this->repository->softDelete($id);
    }
}