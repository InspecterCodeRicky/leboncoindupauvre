<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4eb4d9a3820f771f40a05ba894a58a55
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Faker\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4eb4d9a3820f771f40a05ba894a58a55::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4eb4d9a3820f771f40a05ba894a58a55::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4eb4d9a3820f771f40a05ba894a58a55::$classMap;

        }, null, ClassLoader::class);
    }
}
