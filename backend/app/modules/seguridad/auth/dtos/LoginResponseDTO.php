<?php
class LoginResponseDTO
{
    public int $idUsuario;
    public string $usuario;
    public int $estado;
    public string $token;
    public ?string $refreshToken = null;
    public int|array|null $idEmpleado = null; // acepta entero o arreglo completo

    public function __construct(array $data = [])
    {
        $this->idUsuario     = $data['idUsuario'] ?? 0;
        $this->usuario       = $data['usuario'] ?? '';
        $this->estado        = $data['estado'] ?? 0;
        $this->token         = $data['token'] ?? '';
        $this->refreshToken  = $data['refreshToken'] ?? null;
        $this->idEmpleado    = $data['idEmpleado'] ?? null;
    }

    public function toArray(): array
    {
        // Arreglo base con los datos del usuario y sesión
        $respuesta = [
            'idUsuario'     => $this->idUsuario,
            'usuario'       => $this->usuario,
            'estado'        => $this->estado,
            'token'         => $this->token,
            'refreshToken'   => $this->refreshToken
        ];

        // Si trae datos completos del empleado, los fusionamos directamente
        if (is_array($this->idEmpleado)) {
            $respuesta['idEmpleado']      = $this->idEmpleado['idEmpleado'] ?? null;
            $respuesta['nombres']         = $this->idEmpleado['nombres'] ?? null;
            $respuesta['apellidoPaterno'] = $this->idEmpleado['apellidoPaterno'] ?? null;
            $respuesta['apellidoMaterno'] = $this->idEmpleado['apellidoMaterno'] ?? null;
            $respuesta['numDocumento']    = $this->idEmpleado['numDocumento'] ?? null;
            $respuesta['correo']          = $this->idEmpleado['correo'] ?? null;
            $respuesta['telefono']        = $this->idEmpleado['telefono'] ?? null;
            // Si quieres el estado del empleado, le ponemos un nombre distinto para no confundir
            $respuesta['estadoEmpleado']  = $this->idEmpleado['estado'] ?? null;
        } 
        // Si solo viene el ID numérico del empleado
        elseif (is_int($this->idEmpleado)) {
            $respuesta['idEmpleado'] = $this->idEmpleado;
        }

        return $respuesta;
    }
}