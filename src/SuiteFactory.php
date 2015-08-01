<?php

namespace mindplay\market;

use Composer\Autoload\ClassLoader;
use mindplay\market\adapters\CebeAdapter;
use mindplay\market\adapters\CiconiaAdapter;
use mindplay\market\adapters\CommonMarkAdapter;
use mindplay\market\adapters\ErusevAdapter;
use mindplay\market\adapters\MichelfAdapter;

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
     * @param string      $vendor_path
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
    public static function createTargets()
    {
        $f = new TargetFactory(self::$loader, self::$vendor_path);

        return array(
            $f->createTarget(CebeAdapter::vanilla(), Flavor::VANILLA),
            $f->createTarget(CebeAdapter::extra(), Flavor::EXTRA),
            $f->createTarget(CebeAdapter::github(), Flavor::GITHUB),
            $f->createTarget(ErusevAdapter::vanilla(), Flavor::VANILLA),
            $f->createTarget(ErusevAdapter::extra(), Flavor::EXTRA),
            $f->createTarget(CiconiaAdapter::vanilla(), Flavor::VANILLA),
            $f->createTarget(CiconiaAdapter::github(), Flavor::GITHUB),
            $f->createTarget(MichelfAdapter::vanilla(), Flavor::VANILLA),
            $f->createTarget(MichelfAdapter::extra(), Flavor::EXTRA),
            $f->createTarget(CommonMarkAdapter::vanilla(), Flavor::COMMON),
        );
    }

    /**
     * @return Test[]
     */
    public static function createTests()
    {
//        $target_factory = new TargetFactory(self::$loader, self::$vendor_path);

//        $commonmark_target = $target_factory->createTarget(CommonMarkAdapter::vanilla(), Flavor::COMMON);

        $f = new TestFactory(dirname(__DIR__) . '/vendor');

        $reference_tests = array_merge(
            $f->fromSpec(self::$vendor_path . '/jgm/CommonMark/spec.txt', Flavor::COMMON),
            $f->fromFiles('cebe/markdown/tests/markdown-data', Flavor::VANILLA),
            $f->fromFiles('cebe/markdown/tests/extra-data', Flavor::EXTRA),
            $f->fromFiles('cebe/markdown/tests/github-data', Flavor::GITHUB),
            $f->fromFiles('erusev/parsedown/test/data', Flavor::GITHUB),
            $f->fromFiles('kzykhys/ciconia/test/Ciconia/Resources/core', Flavor::VANILLA, 'md', 'out'),
            $f->fromFiles('kzykhys/ciconia/test/Ciconia/Resources/core/markdown-testsuite', Flavor::VANILLA, 'md', 'out'),
            $f->fromFiles('kzykhys/ciconia/test/Ciconia/Resources/gfm', Flavor::GITHUB, 'md', 'out'),
            $f->fromFiles('kzykhys/ciconia/test/Ciconia/Resources/options/strict/core', Flavor::VANILLA, 'md', 'out'),
            $f->fromFiles('kzykhys/ciconia/test/Ciconia/Resources/options/strict/gfm', Flavor::GITHUB, 'md', 'out'),
            $f->fromFiles('michelf/mdtest/Markdown.mdtest', Flavor::VANILLA, 'text', ['html', 'xhtml']),
            $f->fromFiles('michelf/mdtest/PHP Markdown.mdtest', Flavor::OTHER, 'text', ['html', 'xhtml']),
            $f->fromFiles('michelf/mdtest/PHP Markdown Extra.mdtest', Flavor::EXTRA, 'text', ['html', 'xhtml'])
        );

        return $reference_tests;

        // TODO create tests from CommonMark's spec file and run the reference target below against that
        // instead of against the reference tests

//        return array_merge(
//            $reference_tests,
//            $f->fromTarget($commonmark_target, $reference_tests)
//        );
    }
}
