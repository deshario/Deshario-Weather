<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit12c1f608ad8f3345156740e830365c70
{
    public static $prefixLengthsPsr4 = array (
        'j' => 
        array (
            'juno_okyo\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'juno_okyo\\' => 
        array (
            0 => __DIR__ . '/..' . '/juno_okyo/php-chatfuel-class/src/juno_okyo',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit12c1f608ad8f3345156740e830365c70::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit12c1f608ad8f3345156740e830365c70::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
