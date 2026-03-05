<?php 
class M_welcome extends Model
{

    public function listar()
    {
        $stmt = $this->db->query("SELECT * FROM usuario");

        return $stmt->fetchAll();
    }

}
