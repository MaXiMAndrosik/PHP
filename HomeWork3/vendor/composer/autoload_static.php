<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite0da997f9cddab2ee2cdb1f5d8cf197e
{
    public static $files = array (
        '73a76277cdd754516bf94aa9c0aa6bb3' => __DIR__ . '/../..' . '/src/main.function.php',
        '2c30778c83e7cf1ab5d05f6fb053a212' => __DIR__ . '/../..' . '/src/template.function.php',
        '4497162affd5dbda9202a35ac3a5f40d' => __DIR__ . '/../..' . '/src/file.function.php',
        '8ec98b81f225496aa26adf8636b41833' => __DIR__ . '/../..' . '/src/validate.function.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite0da997f9cddab2ee2cdb1f5d8cf197e::$classMap;

        }, null, ClassLoader::class);
    }
}