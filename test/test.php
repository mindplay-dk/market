<?php

use Composer\Autoload\ClassLoader;

use mindplay\market\Flavor;
use mindplay\market\ResultGroup;
use mindplay\market\Suite;
use mindplay\market\SuiteFactory;
use mindplay\market\TargetFactory;
use mindplay\market\Test;
use mindplay\market\TestFactory;
use mindplay\market\adapters\CebeAdapter;

require dirname(__DIR__) . '/header.php';

test(
    'can get composer package info',
    function () {
        ok(SuiteFactory::$loader instanceof ClassLoader, 'Composer autoloader reference has been set');

        $factory = new TargetFactory(SuiteFactory::$loader, SuiteFactory::$vendor_path);

        $target = $factory->createTarget(CebeAdapter::vanilla(), Flavor::VANILLA);

        $expected = strtr(dirname(__DIR__), DIRECTORY_SEPARATOR, '/') . '/vendor/cebe/markdown';

        eq($target->package_path, $expected, 'can get package path');
        eq($target->package_name, 'cebe/markdown', 'can get package name');
        eq($target->class_name, \cebe\markdown\Markdown::class);
        ok(strlen($target->version) >= 1, 'it has a version number');
        eq(strlen($target->time), 19, 'it has a timestamp');
        eq($target->flavor, Flavor::VANILLA, 'flavor applied');
    }
);

test(
    'all targets can produce results',
    function () {
        $test = new Test();
        $test->input = "# HELLO\n## WORLD\n";
        $test->expected = "<h1>HELLO</h1><h2>WORLD</h2>";

        $suite = new Suite(SuiteFactory::createTargets(), array($test));

        $groups = $suite->run();

        foreach ($groups as $group) {
            foreach ($group->results as $result) {
                ok($result->success, "target {$result->target->package_name} {$result->target->version} works");
            }
        }
    }
);

test(
    'can find a load test data',
    function () {
        $factory = new TestFactory(__DIR__);

        $tests = $factory->createTests('sample-data', Flavor::VANILLA);

        eq(count($tests), 1, 'it finds the sample test-case');
        eq($tests[0]->reference, 'sample-data/headline.md|html', 'it references the source files');
        eq($tests[0]->input, file_get_contents(__DIR__ . '/sample-data/headline.md'), 'test input loaded');
        eq($tests[0]->expected, file_get_contents(__DIR__ . '/sample-data/headline.html'), 'expected output loaded');
        eq($tests[0]->flavor, Flavor::VANILLA, 'it has a flavor :-)');
    }
);

test(
    'can boostrap the test-suite',
    function () {
        $suite = SuiteFactory::create();

        $num_targets = count($suite->targets);
        $num_tests = count($suite->tests);

        ok($num_targets > 0, 'it has targets');
        ok($num_tests > 0, 'it has tests');
    }
);

exit(run());
