<?php

use mindplay\market\ResultGroupSerializer;
use mindplay\market\SuiteFactory;

require dirname(__DIR__) . '/header.php';

$suite = SuiteFactory::create();

$num_targets = count($suite->targets);
$num_tests = count($suite->tests);

echo "Running {$num_tests} tests against {$num_targets} targets...\n\n";

$root = dirname(__DIR__) . '/webroot/results';

$groups = $suite->run();

$dumper = new ResultGroupSerializer();

foreach ($groups as $group) {
    $path = "{$root}/{$group->getBaseName()}.json";

    echo "writing: {$path}\n";

    file_put_contents($path, $dumper->toJSON($group));
}
