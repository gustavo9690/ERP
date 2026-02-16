<?php

class Welcome extends Controller
{

    public function index()
    {
        $data = $this->model("M_welcome")->listar();
        echo $this->json($data);
    }

}