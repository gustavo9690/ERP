<?php

class LoginResponseDTO
{
    public int $idUsuario;
    public string $usuario;
    public int $estado;
    public string $token;
    public ?string $refreshToken = null;
    public $idEmpleado; // puede ser int o array si embed

    public function __construct(array $data = [])
    {
        $this->idUsuario  = $data['idUsuario'] ?? 0;
        $this->usuario    = $data['usuario'] ?? '';
        $this->estado     = $data['estado'] ?? 0;
        $this->token      = $data['token'] ?? '';
        $this->refreshToken = $data['refreshToken'] ?? null;
        $this->idEmpleado = $data['idEmpleado'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'idUsuario'  => $this->idUsuario,
            'usuario'    => $this->usuario,
            'estado'     => $this->estado,
            'token'      => $this->token,
            'refreshToken' => $this->refreshToken,
            'idEmpleado' => $this->idEmpleado
        ];
    }
}