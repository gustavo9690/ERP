<?php

class WelcomeController extends Controller
{

    public function index()
    {
        $data = $this->model("M_welcome")->listar();
        echo $this->json($data);
    }

}