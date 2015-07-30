<?php

namespace mindplay\market;

use Composer\Autoload\ClassLoader;
use RuntimeException;

class TargetFactory
{
    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * @var array contents of "composer.lock"
     */
    private $packages;

    /**
     * @param ClassLoader $loader
     */
    public function __construct(ClassLoader $loader)
    {
        $this->loader = $loader;
        $this->packages = $this->loadPackageInfo();
    }

    /**
     * @param Parser $parser
     * @param string $description
     *
     * @return Target
     */
    public function createTarget(Parser $parser, $description)
    {
        $class_name = $parser->getClassName();

        $class_path = $this->loader->findFile($class_name);

        $class_path = strtr($class_path, DIRECTORY_SEPARATOR, '/');

static $PATTERN = <<<REGEX
#(^.*[\/]\w+[\/]\w+)[\/].*#
REGEX;

        if (preg_match($PATTERN, $class_path, $matches) !== 1) {
            throw new RuntimeException("unable to get package path for class name: {$class_name}");
        }

        $package_path = $matches[1];

        $parts = explode('/', $package_path);
        $count = count($parts);
        $package_name = $parts[$count - 2] . '/' . $parts[$count - 1];

        $target = new Target();

        $target->parser = $parser;
        $target->class_name = $class_name;
        $target->package_path = $package_path;
        $target->package_name = $package_name;
        $target->version = $this->packages[$target->package_name]['version'];
        $target->time = $this->packages[$target->package_name]['time'];
        $target->description = $description;

        return $target;
    }

    /**
     * @return array
     */
    private function loadPackageInfo()
    {
        $json_path = dirname(dirname(__FILE__)) . '/composer.lock';

        $json = json_decode(file_get_contents($json_path), true);

        $packages = array();

        foreach ($json['packages'] as $package) {
            $packages[$package['name']] = $package;
        }

        return $packages;
    }
}
