<?php

namespace mindplay\market;

use Composer\Autoload\ClassLoader;
use mindplay\market\adapters\CebeAdapter;
use mindplay\market\adapters\CiconiaAdapter;
use mindplay\market\adapters\ErusevAdapter;

class SuiteFactory
{
    /**
     * @var ClassLoader
     */
    public static $loader;

    /**
     * @var string vendor root path
     */
    public static $vendor_path;

    /**
     * @param ClassLoader $loader
     * @param string $vendor_path
     */
    public static function bootstrap(ClassLoader $loader, $vendor_path)
    {
        self::$loader = $loader;
        self::$vendor_path = strtr($vendor_path, DIRECTORY_SEPARATOR, '/');
    }

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
        $f = new TargetFactory(self::$loader, self::$vendor_path);

        return array(
            $f->createTarget(CebeAdapter::vanilla(), 'vanilla'),
            $f->createTarget(CebeAdapter::extra(), 'extra'),
            $f->createTarget(CebeAdapter::github(), 'github'),
            $f->createTarget(ErusevAdapter::vanilla(), 'vanilla'),
            $f->createTarget(ErusevAdapter::extra(), 'extra'),
            $f->createTarget(CiconiaAdapter::vanilla(), 'vanilla'),
            $f->createTarget(CiconiaAdapter::github(), 'github'),
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
