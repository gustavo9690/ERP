<?php

class Mapper
{
    /* =====================================================
       MÉTODO PRINCIPAL
    ===================================================== */

    public static function map($data, string $dtoClass)
    {
        if ($data === null) {
            return null;
        }

        // 🔹 Lista (array de registros)
        if (is_array($data) && self::isList($data)) {
            return self::mapList($data, $dtoClass);
        }

        // 🔹 Objeto único
        return self::mapObject($data, $dtoClass);
    }

    /* =====================================================
       MAPEAR UN SOLO OBJETO
    ===================================================== */

    private static function mapObject($data, string $dtoClass)
    {
        if ($data === null) {
            return null;
        }

        // Si es Entity → convertir a array
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        $dto = new $dtoClass();

        foreach ($data as $key => $value) {

            if (!property_exists($dto, $key)) {
                continue;
            }

            // 🔥 Si es array (posible relación)
            if (is_array($value)) {
                $dto->$key = $value; // simple por ahora
            } else {
                $dto->$key = $value;
            }
        }

        return $dto;
    }

    /* =====================================================
       MAPEAR LISTA
    ===================================================== */

    private static function mapList(array $list, string $dtoClass): array
    {
        return array_map(fn($item) => self::mapObject($item, $dtoClass), $list);
    }

    /* =====================================================
       VALIDAR SI ES LISTA
    ===================================================== */

    private static function isList(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}