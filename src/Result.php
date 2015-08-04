<?php

namespace mindplay\market;

class Result
{
    /**
     * @var Target
     */
    public $target;

    /**
     * @var Test
     */
    public $test;

    /**
     * @var string resulting HTML
     */
    public $output;

    /**
     * @var bool TRUE, if the test was a success (expected HTML output)
     */
    public $success;

    /**
     * @var bool TRUE, if the test was an exact match (precise expected output, including whitespace)
     */
    public $exact;
}
