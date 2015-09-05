<?php

use mindplay\market\Flavor;
use mindplay\market\SuiteFactory;

require dirname(__DIR__) . '/header.php';

$suite = SuiteFactory::create();

$num_targets = count($suite->targets);
$num_tests = count($suite->tests);

echo "Running {$num_tests} tests against {$num_targets} targets...\n\n";

$root = dirname(__DIR__) . '/webroot/results';

$results = $suite->run();

$stats = array();

$count = function ($index, $count = true) use (&$stats) {
    $stats[$index] = (@$stats[$index] ?: 0) + ($count ? 1 : 0);
};

$SUCCESS = 'successes';
$FAILURE = 'failures';
$EXACT = 'exact matches';

foreach ($suite->tests as $test) {
    $count('ALL tests');
    $count("{$test->flavor} tests");
}

//foreach ($results as $result) {
//    $count("ALL {$result->target->package_name}:{$result->target->flavor} {$EXACT}", $result->exact);
//    $count("ALL {$result->target->package_name}:{$result->target->flavor} {$SUCCESS}", $result->success);
//    $count("ALL {$result->target->package_name}:{$result->target->flavor} {$FAILURE}", !$result->success);
//}

foreach ($results as $result) {
    if ($result->target->flavor === $result->test->flavor) {
        $count("{$result->target->package_name}:{$result->target->flavor} total");
        $count("{$result->target->package_name}:{$result->target->flavor} {$EXACT}", $result->exact);
        $count("{$result->target->package_name}:{$result->target->flavor} {$SUCCESS}", $result->success);
        $count("{$result->target->package_name}:{$result->target->flavor} {$FAILURE}", !$result->success);
    }
}

#ksort($stats);

foreach ($stats as $title => $value) {
    echo sprintf('[%-50s][%10s]', $title, $value) . "\n";
}
