<?php

use mindplay\market\SuiteFactory;

$loader = require __DIR__ . '/vendor/autoload.php';

SuiteFactory::bootstrap($loader, __DIR__ . '/vendor');
