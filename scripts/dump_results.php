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
    if ($count) {
        $stats[$index] = (@$stats[$index] ?: 0) + 1;
    }
};

$SUCCESS = 'success';
$FAILURE = 'failure';
$EXACT = 'exact';

foreach ($results as $result) {
    $count($result->target->package_name . ":ALL:" . $SUCCESS, $result->success);
    $count($result->target->package_name . ":ALL:" . $FAILURE, !$result->success);
    $count($result->target->package_name . ":ALL:" . $EXACT, $result->exact);

    if ($result->target->flavor === $result->test->flavor) {
        $count($result->target->package_name . ":{$result->test->flavor}:" . $SUCCESS, $result->success);
        $count($result->target->package_name . ":{$result->test->flavor}:" . $FAILURE, !$result->success);
        $count($result->target->package_name . ":{$result->test->flavor}:" . $EXACT, $result->exact);
    }
}

foreach ($suite->tests as $test) {
    $count('#' . $test->flavor);
}

ksort($stats);

foreach ($stats as $title => $value) {
    echo sprintf('[%-40s][%10s]', $title, $value) . "\n";
}
