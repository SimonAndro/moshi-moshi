<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit857c1b609bf7bcb89c755783ca8d0e6d
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Emojione\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Emojione\\' => 
        array (
            0 => __DIR__ . '/..' . '/emojione/emojione/lib/php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit857c1b609bf7bcb89c755783ca8d0e6d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit857c1b609bf7bcb89c755783ca8d0e6d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit857c1b609bf7bcb89c755783ca8d0e6d::$classMap;

        }, null, ClassLoader::class);
    }
}
