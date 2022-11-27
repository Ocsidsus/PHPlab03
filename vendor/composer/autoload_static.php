<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita98c3fba96c03bbc67ce26252f4b9363
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/vendor' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita98c3fba96c03bbc67ce26252f4b9363::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita98c3fba96c03bbc67ce26252f4b9363::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita98c3fba96c03bbc67ce26252f4b9363::$classMap;

        }, null, ClassLoader::class);
    }
}
