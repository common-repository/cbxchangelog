<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit402c9c4c9714db9da790c6d217a3a291
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Michelf\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Michelf\\' => 
        array (
            0 => __DIR__ . '/..' . '/michelf/php-markdown/Michelf',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit402c9c4c9714db9da790c6d217a3a291::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit402c9c4c9714db9da790c6d217a3a291::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit402c9c4c9714db9da790c6d217a3a291::$classMap;

        }, null, ClassLoader::class);
    }
}