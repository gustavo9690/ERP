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

        // ⚠️ Recomendación: cambiar a password_verify
        if ($usuario->clave !== md5($dto->clave)) {
            throw new Exception('Clave incorrecta');
        }

        $tokenData = [
            'idUsuario' => $usuario->idUsuario,
            'usuario'   => $usuario->usuario,
            'estado'    => $usuario->estado
        ];

        $accessToken = JwtHelper::generate($tokenData, 3600, 'access');
        $refreshToken = JwtHelper::generate(
            ['idUsuario' => $usuario->idUsuario],
            60 * 60 * 24 * 7,
            'refresh'
        );

        $response = Mapper::map($usuario, LoginResponseDTO::class);
        $response->token = $accessToken;
        $response->refreshToken = $refreshToken;

        return $response;
    }

    public function refresh(string $refreshToken): array
    {
        $decoded = JwtHelper::validate($refreshToken);

        if (($decoded->type ?? '') !== 'refresh') {
            throw new Exception('Refresh token inválido');
        }

        $data = $decoded->data ?? null;
        $idUsuario = $data->idUsuario ?? null;

        if (!$idUsuario) {
            throw new Exception('Refresh token inválido');
        }

        $usuario = $this->usuarioRepository->findByIdUsuarioAndEstado((int)$idUsuario, 1);

        if (!$usuario) {
            throw new Exception('Usuario no encontrado o inactivo');
        }

        $newAccessToken = JwtHelper::generate([
            'idUsuario' => $usuario->idUsuario,
            'usuario'   => $usuario->usuario,
            'estado'    => $usuario->estado
        ], 3600, 'access');

        return [
            'token' => $newAccessToken
        ];
    }

    // ✅ Alineado con el formato unificado
    public function me(object $authUser): array
    {
        if (!$authUser) {
            throw new Exception('Usuario no autenticado');
        }

        $usuario = $this->usuarioRepository->findByIdUsuario((int)$authUser->idUsuario);

        if (!$usuario) {
            throw new Exception('Usuario no encontrado');
        }

        $response = Mapper::map($usuario, LoginResponseDTO::class);
        return $response->toArray();
    }
}