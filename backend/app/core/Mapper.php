<?php
class Mapper
{

    public static function map($data, string $dtoClass)
    {

        // 🔹 Si es lista
        if (is_array($data) && isset($data[0])) {
            return self::mapList($data, $dtoClass);
        }

        // 🔹 Si es un solo registro
        return self::mapObject($data, $dtoClass);

    }

    private static function mapObject($data, string $dtoClass)
    {

        if ($data === null) {
            return null;
        }

        $dto = new $dtoClass();

        // si viene entity
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        foreach ($data as $key => $value) {

            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }

        }

        return $dto;

    }

    public static function mapList(array $list, string $dtoClass): array
    {

        $result = [];

        foreach ($list as $row) {
            $result[] = self::mapObject($row, $dtoClass);
        }

        return $result;

    }

}