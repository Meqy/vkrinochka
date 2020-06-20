<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf3c361f6c25d731ec9d853417a1317e5
{
    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'meqy\\vkrinochka\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'meqy\\vkrinochka\\' => 
        array (
            0 => __DIR__ . '/..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf3c361f6c25d731ec9d853417a1317e5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf3c361f6c25d731ec9d853417a1317e5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
