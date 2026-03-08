<?php
class EmpleadoController extends Controller
{
    private EmpleadoEntity $empleado;

    public function __construct()
    {
        $this->empleado = new EmpleadoEntity();
    }
   public function obtener($estado)
    {
        $empleado = EmpleadoEntity::findByEstado($estado);
        if (!$empleado) {
            Response::json([
                'message' => 'Empleado no encontrado'
            ], 404);
            return;
        }

        $dto = Mapper::map($empleado, EmpleadoDTO::class);

        Response::json($dto);
    }
}