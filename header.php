<?php

use mindplay\market\SuiteFactory;

error_reporting(E_ALL);

$loader = require __DIR__ . '/vendor/autoload.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $error = new ErrorException($errstr, 0, $errno, $errfile, $errline);
    if ($error->getSeverity() & error_reporting()) {
        throw $error;
    }
});

SuiteFactory::bootstrap($loader, __DIR__ . '/vendor');
