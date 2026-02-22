<?php
class Auth extends Controller
{
    private M_auth $m_auth;

    public function __construct()
    {
        $this->m_auth = new M_auth();
    }

    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $usuario = $input['usuario'] ?? '';
        $clave = $input['clave'] ?? '';

        if (!$usuario || !$clave) {
            Response::json([
                'success' => false,
                'message' => 'Datos incompletos'
            ], 400);
        }

        $user = $this->m_auth->getUserByUsuario($usuario);

        if (!$user || !password_verify($clave, $user['clave'])) {
            Response::json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $token = JwtHelper::generate([
            'id' => $user['id'],
            'usuario' => $user['usuario'],
        ]);

        Response::json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'nombre' => $user['nombre']
            ]
        ]);
    }
}