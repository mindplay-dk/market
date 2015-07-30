<?php

namespace mindplay\market;

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
     * @param Test[] $tests
     */
    public function __construct($targets, $tests)
    {
        $this->targets = $targets;
        $this->tests = $tests;
    }

    /**
     * @return Result[]
     */
    public function run()
    {
        $results = array();

        foreach ($this->targets as $target) {
            foreach ($this->tests as $test) {
                $results[] = $this->test($target, $test);
            }
        }

        return $results;
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
    protected function compareHTML($actual, $expected)
    {
        static $FROM = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s'];
        static $TO = ['>', '<', '\\1', '><'];

        return preg_replace($FROM, $TO, $actual) === preg_replace($FROM, $TO, $expected);
    }

    /**
     * @param Target $target
     * @param Test $test
     *
     * @return Result
     */
    protected function test(Target $target, Test $test)
    {
        $result = new Result();

        $output = $target->adapter->parse($test->input);

        $exact = $output === $test->expected;

        $result->source = $target;
        $result->test = $test;
        $result->output = $output;
        $result->exact = $exact;
        $result->success = $exact ?: $this->compareHTML($output, $test->expected);

        return $result;
    }
}
