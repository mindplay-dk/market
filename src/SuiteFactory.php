<?php

namespace mindplay\market;

use Composer\Autoload\ClassLoader;
use mindplay\market\parsers\CebeParser;

class SuiteFactory
{
    /**
     * @var ClassLoader
     */
    public static $loader;

    /**
     * @return Suite
     */
    public static function create()
    {
        return new Suite(self::createTargets(), self::createTests());
    }

    /**
     * @return Target[]
     */
    private static function createTargets()
    {
        $f = new TargetFactory(self::$loader);

        return array(
            $f->createTarget(new CebeParser(), 'vanilla'),
        );
    }

    /**
     * @return Test[]
     */
    private static function createTests()
    {
        return array();
    }
}
