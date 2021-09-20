<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit48adba4df5da21915ab34101a5c3db70
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'IdiormMethodMissingException' => __DIR__ . '/..' . '/j4mie/idiorm/idiorm.php',
        'IdiormResultSet' => __DIR__ . '/..' . '/j4mie/idiorm/idiorm.php',
        'IdiormString' => __DIR__ . '/..' . '/j4mie/idiorm/idiorm.php',
        'IdiormStringException' => __DIR__ . '/..' . '/j4mie/idiorm/idiorm.php',
        'ORM' => __DIR__ . '/..' . '/j4mie/idiorm/idiorm.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit48adba4df5da21915ab34101a5c3db70::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit48adba4df5da21915ab34101a5c3db70::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit48adba4df5da21915ab34101a5c3db70::$classMap;

        }, null, ClassLoader::class);
    }
}