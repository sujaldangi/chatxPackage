<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0a484d764e415313e3c214b6721ea6e9
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sujal\\Chatx\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sujal\\Chatx\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0a484d764e415313e3c214b6721ea6e9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0a484d764e415313e3c214b6721ea6e9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0a484d764e415313e3c214b6721ea6e9::$classMap;

        }, null, ClassLoader::class);
    }
}
