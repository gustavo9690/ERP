<?php
class Auth extends Controller
{
    private Usuario $usuario;
    private Empleado $empleado;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->empleado = new Empleado();
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $usuarioInput = $data['usuario'] ?? null;
        $claveInput   = $data['clave'] ?? null;

        if (!$usuarioInput || !$claveInput) {
            http_response_code(401);
            echo json_encode(["error" => "Usuario y clave requeridos"]);
            return;
        }

        $usuarios = Usuario::findByUsuarioAndEstado($usuarioInput, 1);

        if (empty($usuarios)) {
            http_response_code(401);
            echo json_encode(["error" => "Usuario no existente o inactivo"]);
            return;
        }

        $usuario = new Usuario($usuarios[0]);

        // 🔐 VALIDACIÓN MD5
        if ($usuario->clave !== md5($claveInput)) {
            http_response_code(401);
            echo json_encode(["error" => "Clave incorrecta"]);
            return;
        }

        $empleado = Empleado::find($usuario->idEmpleado);

         $token = JwtHelper::generate([
            "id" => $usuario->idUsuario,
            "id_usuario" => $usuario->idUsuario,
            "email" => $usuario->usuario,
            "id_empresa" => $empleado->idEmpresa,
            "nombres" => $empleado->nombres,
            "apellido_paterno" => $empleado->apellidoPaterno,
            "apellido_materno" => $empleado->apellidoMaterno
        ]);

        echo json_encode([
            "token" => $token
        ]);
    }
}