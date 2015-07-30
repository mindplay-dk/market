<?php

namespace mindplay\market;

use Composer\Autoload\ClassLoader;
use RuntimeException;

abstract class ComposerHelper
{
    /**
     * @var ClassLoader
     */
    private static $loader;

    /**
     * @var array contents of "composer.lock"
     */
    private static $packages;

    /**
     * @param ClassLoader $loader
     */
    public static function setLoader(ClassLoader $loader)
    {
        self::$loader = $loader;

        $json_path = dirname(dirname(__FILE__)) . '/composer.lock';

        $json = json_decode(file_get_contents($json_path), true);

        self::$packages = array();

        foreach ($json['packages'] as $package) {
            self::$packages[$package['name']] = $package;
        }
    }

    /**
     * @param string $class_name
     *
     * @return PackageInfo
     */
    public static function getPackageInfo($class_name)
    {
        $path = self::$loader->findFile($class_name);

        $path = strtr($path, DIRECTORY_SEPARATOR, '/');

static $PATTERN = <<<REGEX
#(^.*[\/]\w+[\/]\w+)[\/].*#
REGEX;

        if (preg_match($PATTERN, $path, $matches) !== 1) {
            throw new RuntimeException("unable to get package path for class name: {$class_name}");
        }

        $info = new PackageInfo();

        $info->class_name = $class_name;

        $info->path = $matches[1];

        $parts = explode('/', $info->path);
        $count = count($parts);

        $info->name = $parts[$count-2] . '/' . $parts[$count-1];

        $info->version = self::$packages[$info->name]['version'];
        $info->time = self::$packages[$info->name]['time'];

        return $info;
    }
}
