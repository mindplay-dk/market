<?php

use mindplay\market\ComposerHelper;

require dirname(__DIR__) . '/header.php';

test(
    'can get composer package info',
    function () {
        $class_name = \cebe\markdown\Markdown::class;

        $info = ComposerHelper::getPackageInfo($class_name);

        $expected = strtr(dirname(__DIR__), DIRECTORY_SEPARATOR, '/') . '/vendor/cebe/markdown';

        eq($info->path, $expected, 'can get package path');
        eq($info->name, 'cebe/markdown', 'can get package name');
        eq($info->class_name, $class_name);
        ok(strlen($info->version) >= 1, 'it has a version number');
        eq(strlen($info->time), 19, 'it has a timestamp');
    }
);

exit(run());
