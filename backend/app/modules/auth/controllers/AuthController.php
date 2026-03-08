<?php
class AuthController extends Controller
{
    private UsuarioEntity $usuario;
    private EmpleadoEntity $empleado;

    public function __construct()
    {
        $this->usuario = new UsuarioEntity();
        $this->empleado = new EmpleadoEntity();
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $usuarioInput = $data['usuario'] ?? null;
        $claveInput   = $data['clave'] ?? null;

        if (!$usuarioInput || !$claveInput) {
            Response::error("Usuario y clave requeridos", 401);
        }


        $usuarios = UsuarioEntity::findByUsuarioAndEstado($usuarioInput, 1);

        if (empty($usuarios)) {
            Response::error("Usuario no existente o inactivo", 401);
        }

        $usuario = new UsuarioEntity($usuarios[0]->toarray());

        // 🔐 VALIDACIÓN MD5
        if ($usuario->clave !== md5($claveInput)) {
            Response::error("Clave incorrecta",401);
        }

        $empleado = EmpleadoEntity::find($usuario->idEmpleado);

        $token = JwtHelper::generate([
            "id" => $usuario->idUsuario,
            "id_usuario" => $usuario->idUsuario,
            "email" => $usuario->usuario,
            "id_empresa" => $empleado->idEmpresa,
            "nombres" => $empleado->nombres,
            "apellido_paterno" => $empleado->apellidoPaterno,
            "apellido_materno" => $empleado->apellidoMaterno
        ]);

        Response::success([
            "token" => $token
        ]);

    }
}
