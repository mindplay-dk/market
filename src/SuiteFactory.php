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
        return new Suite(self::createTargets());
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
}
