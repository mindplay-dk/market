<?php

namespace mindplay\market;

class Result
{
    /**
     * @var Target
     */
    public $source;

    /**
     * @var Test
     */
    public $test;

    /**
     * @var string resulting HTML
     */
    public $output;

    /**
     * @var bool TRUE, if the test was a success
     */
    public $success;

    /**
     * @var bool TRUE, if the test was an exact match success
     */
    public $exact;
}
