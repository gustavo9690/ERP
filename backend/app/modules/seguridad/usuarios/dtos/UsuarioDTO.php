<?php
class UsuarioDTO
{
    public ?int $idUsuario = null;
    public ?string $usuario = null;
    public ?string $clave = null;
    public ?int $estado = null;
    public $idEmpleado = null;

    public function __construct(array $data = [])
    {
        $this->idUsuario  = $data['idUsuario'] ?? null;
        $this->usuario    = $data['usuario'] ?? null;
        $this->clave      = $data['clave'] ?? null;
        $this->estado     = $data['estado'] ?? null;
        $this->idEmpleado = $data['idEmpleado'] ?? null;
    }

    public function toResponse(): array
    {
        return [
            'idUsuario'  => $this->idUsuario,
            'usuario'    => $this->usuario,
            'estado'     => $this->estado,
            'idEmpleado' => $this->idEmpleado
        ];
    }
}