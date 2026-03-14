<?php
class RolesController extends Controller
{
    private RolesEntity $roles;

    public function __construct()
    {
        $this->roles = new RolesEntity();
    }
   public function obtener()
    {
        $roles = RolesEntity::all();
        if (!$roles) {
            Response::error("Roles no encontrados",401);
        }

        $dto = Mapper::map($roles, RolesResponseDTO::class);

        Response::success([
            "roles" => $dto
        ]);
    }

    public function crear()
    {
        $dto = RolesRequestDTO::fromRequest();

        if (!$dto->nombre) {
            Response::error("El nombre del rol es obligatorio", 422);
        }

        $rol = new RolesEntity([
            'nombre' => $dto->nombre,
            'descripcion' => $dto->descripcion
        ]);

        $rol->save();

        Response::success([
            "message" => "Rol creado correctamente"
        ],201);
    }

    public function actualizar()
    {
        $dto = RolesRequestDTO::fromRequest();

        if (!$dto->idRol) {
            Response::error("ID requerido",422);
        }

        $rol = new RolesEntity([
            'idRol' => $dto->idRol,
            'nombre' => $dto->nombre,
            'descripcion' => $dto->descripcion,
            'estado' => $dto->estado
        ]);

        $rol->update();

        Response::success([
            "message" => "Rol actualizado correctamente"
        ]);
    }
}