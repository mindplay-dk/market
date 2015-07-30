<?php

use Composer\Autoload\ClassLoader;
use mindplay\market\SuiteFactory;
use mindplay\market\TargetFactory;

require dirname(__DIR__) . '/header.php';

test(
    'can get composer package info',
    function () {
        $class_name = \cebe\markdown\Markdown::class;

        ok(SuiteFactory::$loader instanceof ClassLoader, 'Composer autoloader reference has been set');

        $factory = new TargetFactory(SuiteFactory::$loader);

        $target = $factory->createTarget(new \mindplay\market\parsers\CebeParser(), 'test');

        $expected = strtr(dirname(__DIR__), DIRECTORY_SEPARATOR, '/') . '/vendor/cebe/markdown';

        eq($target->package_path, $expected, 'can get package path');
        eq($target->package_name, 'cebe/markdown', 'can get package name');
        eq($target->class_name, $class_name);
        ok(strlen($target->version) >= 1, 'it has a version number');
        eq(strlen($target->time), 19, 'it has a timestamp');
        eq($target->description, 'test', 'description applied');
    }
);

exit(run());
