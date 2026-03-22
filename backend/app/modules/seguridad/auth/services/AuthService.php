<?php

class AuthService
{
    private UsuarioRepository $usuarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function login(LoginRequestDTO $dto): LoginResponseDTO
    {
        $dto->validate();

        $usuario = $this->usuarioRepository->findByUsuarioAndEstado($dto->usuario, 1);

        if (!$usuario) {
            throw new Exception('Usuario no encontrado o inactivo');
        }

        // Ajusta esto según tu forma de encriptar
        if ($usuario->clave !== md5($dto->clave)) {
            throw new Exception('Clave incorrecta');
        }

        $payload = [
            'idUsuario' => $usuario->idUsuario,
            'usuario'   => $usuario->usuario,
            'estado'    => $usuario->estado,
            'iat'       => time()
        ];

        $token = JwtHelper::generate($payload);

        $response = Mapper::map($usuario, LoginResponseDTO::class);
        $response->token = $token;

        return $response;
    }

    public function me($authUser): array
    {
        if (!$authUser) {
            throw new Exception('Usuario no autenticado');
        }

        $usuario = $this->usuarioRepository->findByIdUsuario((int)$authUser->idUsuario);

        if (!$usuario) {
            throw new Exception('Usuario no encontrado');
        }

        return [
            'idUsuario'  => $usuario->idUsuario,
            'usuario'    => $usuario->usuario,
            'estado'     => $usuario->estado,
            'idEmpleado' => $usuario->idEmpleado
        ];
    }
}