<?php

class Controller
{

    protected function json($data): void
    {
        Response::json($data);
    }

    protected function model(string $model)
    {
        if (class_exists($model))
        {
            return new $model();
        }

        throw new Exception("Modelo no existe: " . $model);
    }

}
