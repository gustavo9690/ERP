<?php

class LoginRequestDTO
{
    public string $usuario = '';
    public string $clave = '';

    public function __construct(array $data = [])
    {
        $this->usuario = trim($data['usuario'] ?? '');
        $this->clave   = trim($data['clave'] ?? '');
    }

    public static function fromRequest(): self
    {
        $input = $_POST;

        if (empty($input)) {
            $json = file_get_contents('php://input');
            $input = json_decode($json, true) ?? [];
        }

        return new self($input);
    }

    public function validate(): void
    {
        if ($this->usuario === '') {
            throw new Exception('Usuario es requerido');
        }

        if (strlen($this->usuario) < 3) {
            throw new Exception('Usuario inválido');
        }

        if ($this->clave === '') {
            throw new Exception('Clave es requerida');
        }
    }
}