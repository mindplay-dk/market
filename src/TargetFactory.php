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
     * @var string
     */
    private $vendor_path;

    /**
     * @var array contents of "composer.lock"
     */
    private $packages;

    /**
     * @param ClassLoader $loader
     */
    public function __construct(ClassLoader $loader, $vendor_path)
    {
        $this->loader = $loader;
        $this->vendor_path = $vendor_path;
        $this->packages = $this->loadPackageInfo();
    }

    /**
     * @param Adapter $adapter
     * @param string $description
     *
     * @return Target
     */
    public function createTarget(Adapter $adapter, $description)
    {
        $class_name = $adapter->getClassName();

        $class_path = $this->loader->findFile($class_name);
        $class_path = strtr($class_path, DIRECTORY_SEPARATOR, '/');
        $class_path = substr($class_path, strlen($this->vendor_path) + 1);

        $parts = explode('/', $class_path);
        $package_name = $parts[0] . '/' . $parts[1];

        if (!isset($this->packages[$package_name])) {
            throw new RuntimeException("internal error: {$package_name} not found in composer.lock ({$class_path})");
        }

        $target = new Target();

        $target->adapter = $adapter;
        $target->class_name = $class_name;
        $target->package_path = $this->vendor_path . '/' . $package_name;
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
