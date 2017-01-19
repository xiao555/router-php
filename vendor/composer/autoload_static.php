<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdb31a51417ad40eb0ef3aa46d240d739
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phroute\\Phroute\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phroute\\Phroute\\' => 
        array (
            0 => __DIR__ . '/..' . '/phroute/phroute/src/Phroute',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdb31a51417ad40eb0ef3aa46d240d739::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdb31a51417ad40eb0ef3aa46d240d739::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
