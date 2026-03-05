<?php

class Autoload
{

    public static function register(): void
    {
        spl_autoload_register([self::class, 'loadClass']);
    }

    private static function loadClass(string $class): void
    {

        // rutas base
        $baseDir = __DIR__ . '/../';

        $directories = [

            // core
            $baseDir . 'core/',

            // helpers
            $baseDir . 'helpers/',

            // config
            $baseDir . 'config/',
            

        ];

        // buscar módulos dinámicamente
        $modules = glob($baseDir . 'modules/*', GLOB_ONLYDIR);

        foreach ($modules as $module)
        {
            $directories[] = $module . '/controllers/';
            $directories[] = $module . '/models/';
            $directories[] = $module . '/services/';
            $directories[] = $module . '/entities/';
        }

        // buscar archivo
        foreach ($directories as $directory)
        {

            $file = $directory . $class . '.php';

            if (file_exists($file))
            {
                require_once $file;
                return;
            }

        }

    }

}
