<?php

class Autoload
{
    public static function register(): void
    {
        spl_autoload_register([self::class, 'loadClass']);
    }

    private static function loadClass(string $class): void
    {
        $baseDir = __DIR__ . '/../';

        $directories = [

            // core (incluye subcarpetas)
            $baseDir . 'core/',

            // helpers
            $baseDir . 'helpers/',

            // config
            $baseDir . 'config/',
        ];

        // 🔥 módulos dinámicos
        $modules = glob($baseDir . 'modules/*', GLOB_ONLYDIR);

        foreach ($modules as $module) {

            $submodules = glob($module . '/*', GLOB_ONLYDIR);

            foreach ($submodules as $submodule) {

                $directories[] = $submodule . '/controllers/';
                $directories[] = $submodule . '/services/';
                $directories[] = $submodule . '/entities/';
                $directories[] = $submodule . '/repositories/';
                $directories[] = $submodule . '/dtos/';
                $directories[] = $submodule . '/middleware/';
            }
        }

        // 🔥 buscar clase
        foreach ($directories as $directory) {

            $file = self::findFileRecursive($directory, $class . '.php');

            if ($file) {
                require_once $file;
                return;
            }
        }
    }

    /* =====================================================
       BÚSQUEDA RECURSIVA
    ===================================================== */

    private static function findFileRecursive(string $directory, string $fileName): ?string
    {
        if (!is_dir($directory)) {
            return null;
        }

        $files = scandir($directory);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $directory . $file;

            if (is_dir($fullPath)) {
                $result = self::findFileRecursive($fullPath . '/', $fileName);
                if ($result) {
                    return $result;
                }
            } else {
                if ($file === $fileName) {
                    return $fullPath;
                }
            }
        }

        return null;
    }
}