<?php

use Composer\Autoload\ClassLoader;
use mindplay\market\adapters\CebeAdapter;
use mindplay\market\Flavor;
use mindplay\market\Suite;
use mindplay\market\SuiteFactory;
use mindplay\market\Target;
use mindplay\market\TargetFactory;
use mindplay\market\Test;
use mindplay\market\TestFactory;

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
        $test = new Test(
            '',
            "# HELLO\n## WORLD\n",
            "<h1>HELLO</h1><h2>WORLD</h2>",
            ''
        );

        $targets = SuiteFactory::createTargets();

        $suite = new Suite($targets, array($test));

        $results = $suite->run();

        eq(count($results), count($targets), count($targets) . 'results returned');

        foreach ($results as $result) {
            ok($result->success, "target {$result->target->package_name} {$result->target->version} works");
        }
    }
);

test(
    'can find a load test data',
    function () {
        $factory = new TestFactory(__DIR__);

        $tests = $factory->fromFiles('sample-data', Flavor::VANILLA);

        eq(count($tests), 1, 'it finds the sample test-case');
        eq($tests[0]->reference, 'sample-data/headline.md|html', 'it references the source files');
        eq($tests[0]->input, file_get_contents(__DIR__ . '/sample-data/headline.md'), 'test input loaded');
        eq($tests[0]->expected, file_get_contents(__DIR__ . '/sample-data/headline.html'), 'expected output loaded');
        eq($tests[0]->flavor, Flavor::VANILLA, 'it has a flavor :-)');
    }
);

test(
    'can load CommonMark specs',
    function () {
        $factory = new TestFactory(dirname(__DIR__) . '/vendor');

        $tests = $factory->fromSpec(dirname(__DIR__) . '/vendor/jgm/CommonMark/spec.txt', Flavor::COMMON);

        eq($tests[0]->reference, 'jgm/CommonMark/spec.txt#1');
    }
);

test(
    'can derive test data from targets',
    function () {
        $test_factory = new TestFactory(__DIR__);

        $tests = $test_factory->fromFiles('sample-data', Flavor::VANILLA);

        $target_factory = new TargetFactory(SuiteFactory::$loader, SuiteFactory::$vendor_path);

        $target = $target_factory->createTarget(CebeAdapter::vanilla(), Flavor::VANILLA);

        $tests = $test_factory->fromTarget($target, $tests);

        $input = file_get_contents(__DIR__ . '/sample-data/headline.md');
        $expected = $target->adapter->parse($input);

        eq(count($tests), 1, 'it derives a test-case');
        ok(fnmatch('cebe/markdown/*/VANILLA#sample-data/headline.md|html', $tests[0]->reference),
            'it references the original source file');
        eq($tests[0]->input, $input, 'test input loaded');
        eq($tests[0]->expected, $expected, 'expected output generated');
        eq($tests[0]->flavor, Flavor::VANILLA, 'it has the same flavor');
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

test(
    'should throw for duplicates in test suite',
    function () {
        $target_a = new Target();
        $target_a->package_name = 'foo/bar';
        $target_a->version = '0.0.1';
        $target_a->flavor = Flavor::VANILLA;

        $target_b = clone $target_a;

        $test_a = new Test('foo', 'a', 'a', Flavor::VANILLA);
        $test_b = new Test('foo', 'b', 'b', Flavor::EXTRA);

        expect(
            RuntimeException::class,
            'should throw for duplicate targets',
            function () use ($target_a, $target_b, $test_a) {
                $suite = new Suite(array($target_a, $target_b), array($test_a));
            }
        );

        expect(
            RuntimeException::class,
            'should throw for duplicate tests',
            function () use ($target_a, $test_a, $test_b) {
                $suite = new Suite(array($target_a), array($test_a, $test_b));
            }
        );
    }
);

test(
    'test-base metrics',
    function () {
        /**
         * @var $distinct Test[][]
         * @var $dupes    Test[][]
         */

        $tests = SuiteFactory::createTests();

        $distinct = array();

        foreach ($tests as $test) {
            $distinct[$test->hash][] = $test;
        }

        $dupes = array_filter(
            $distinct,
            function ($tests) {
                return count($tests) > 1;
            }
        );

        $num_dupes = array_sum(
            array_map(
                function ($tests) {
                    return count($tests);
                },
                $dupes
            )
        );

        echo '* total number of Markdown tests installed: ' . count($tests) . "\n";
        echo '* number of Markdown tests duplicating another test: ' . $num_dupes . "\n";

//        foreach ($dupes as $duped_tests) {
//            echo count($duped_tests) . " duplicates:\n";
//            foreach ($duped_tests as $duped_test) {
//                echo "- " . $duped_test->reference . "\n";
//            }
//        }
    }
);

exit(run());
