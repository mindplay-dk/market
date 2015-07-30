<?php

use Composer\Autoload\ClassLoader;
use mindplay\market\Suite;
use mindplay\market\SuiteFactory;
use mindplay\market\TargetFactory;
use mindplay\market\Test;
use mindplay\market\TestFactory;

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

test(
    'can find a load test data',
    function () {
        $factory = new TestFactory(__DIR__);

        $tests = $factory->createTests('sample-data');

        eq(count($tests), 1, 'it finds the sample test-case');
        eq($tests[0]->reference, 'sample-data/headline.md|html', 'it references the source files');
        eq($tests[0]->input, file_get_contents(__DIR__ . '/sample-data/headline.md'), 'test input loaded');
        eq($tests[0]->expected, file_get_contents(__DIR__ . '/sample-data/headline.html'), 'expected output loaded');
    }
);

test(
    'can configure test-suite',
    function () {
        $suite = SuiteFactory::create();

        ok(count($suite->targets) > 0, 'it has targets');
        ok(count($suite->tests) > 0, 'it has tests');
    }
);

exit(run());
