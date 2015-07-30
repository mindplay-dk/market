<?php

use Composer\Autoload\ClassLoader;
use mindplay\market\Suite;
use mindplay\market\SuiteFactory;
use mindplay\market\TargetFactory;
use mindplay\market\Test;

require dirname(__DIR__) . '/header.php';

test(
    'can get composer package info',
    function () {
        ok(SuiteFactory::$loader instanceof ClassLoader, 'Composer autoloader reference has been set');

        $factory = new TargetFactory(SuiteFactory::$loader);

        $target = $factory->createTarget(new \mindplay\market\parsers\CebeParser(), 'test');

        $expected = strtr(dirname(__DIR__), DIRECTORY_SEPARATOR, '/') . '/vendor/cebe/markdown';

        eq($target->package_path, $expected, 'can get package path');
        eq($target->package_name, 'cebe/markdown', 'can get package name');
        eq($target->class_name, \cebe\markdown\Markdown::class);
        ok(strlen($target->version) >= 1, 'it has a version number');
        eq(strlen($target->time), 19, 'it has a timestamp');
        eq($target->description, 'test', 'description applied');
    }
);

test(
    'can test parsers',
    function () {
        $factory = new TargetFactory(SuiteFactory::$loader);

        $target = $factory->createTarget(new \mindplay\market\parsers\CebeParser(), 'test');

        $test = new Test();
        $test->input = "# HELLO\n## WORLD\n";
        $test->expected = "<h1>HELLO</h1><h2>WORLD</h2>";

        $suite = new Suite(array($target), array($test));

        $results = $suite->run();

        eq($results[0]->success, true, 'completed a simple test');
    }
);

exit(run());
