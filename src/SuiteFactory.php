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
        $f = new TestFactory(dirname(__DIR__) . '/vendor');

        // TODO create tests from CommonMark's spec file

        return array_merge(
            $f->createTests('cebe/markdown/tests/markdown-data'),
            $f->createTests('cebe/markdown/tests/extra-data'),
            $f->createTests('cebe/markdown/tests/github-data'),
            $f->createTests('erusev/parsedown/test/data'),
            $f->createTests('kzykhys/ciconia/test/Ciconia/Resources/core', 'md', 'out'),
            $f->createTests('kzykhys/ciconia/test/Ciconia/Resources/core/markdown-testsuite', 'md', 'out'),
            $f->createTests('kzykhys/ciconia/test/Ciconia/Resources/gfm', 'md', 'out'),
            $f->createTests('kzykhys/ciconia/test/Ciconia/Resources/options/strict/core', 'md', 'out'),
            $f->createTests('kzykhys/ciconia/test/Ciconia/Resources/options/strict/gfm', 'md', 'out')
        );
    }
}
