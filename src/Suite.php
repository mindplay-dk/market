<?php

namespace mindplay\market;

use Exception;

class Suite
{
    /**
     * @var Target
     */
    public $targets = array();

    /**
     * @var Test[]
     */
    public $tests = array();

    /**
     * @param Target[] $targets
     * @param Test[]   $tests
     */
    public function __construct($targets, $tests)
    {
        $this->targets = $targets;
        $this->tests = $tests;
    }

    /**
     * @return ResultGroup[] lists of result groups for each Target tested
     */
    public function run()
    {
        /**
         * @var ResultGroup[] $groups
         */

        $groups = array();

        foreach ($this->targets as $target) {
            $group = new ResultGroup();
            $group->target = $target;

            foreach ($this->tests as $test) {
                $group->results[] = self::test($test, $target);
            }

            $groups[] = $group;
        }

        return $groups;
    }

    /**
     * Compare two HTML fragments.
     *
     * http://stackoverflow.com/a/26727310/283851
     *
     * @param string $actual
     * @param string $expected
     *
     * @return bool
     */
    protected static function compareHTML($actual, $expected)
    {
        static $FROM = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s'];
        static $TO = ['>', '<', '\\1', '><'];

        return preg_replace($FROM, $TO, $actual) === preg_replace($FROM, $TO, $expected);
    }

    /**
     * @param Test   $test
     * @param Target $target
     *
     * @return Result
     */
    public static function test(Test $test, Target $target)
    {
        $result = new Result();

        $output = null;
        $error = null;

        try {
            $output = $target->adapter->parse($test->input);
        } catch (Exception $e) {
            $error = $e->getFile() . '#' . $e->getLine() . ': ' . $e->getMessage();
        }

        $exact = $output === $test->expected;

        $result->test = $test;
        $result->output = $output ?: "ERROR: {$error}";
        $result->exact = $exact;
        $result->success = $exact ?: self::compareHTML($output, $test->expected);

        return $result;
    }
}
