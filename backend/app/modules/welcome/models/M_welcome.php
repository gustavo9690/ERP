<?php 
class M_welcome extends Model
{

    public function listar()
    {
        $stmt = $this->db->query("SELECT * FROM usuarios");

        return $stmt->fetchAll();
    }

}
