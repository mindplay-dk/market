<?php

namespace mindplay\market;

/**
 * This model represents a group of results for a specific target.
 */
class ResultGroup
{
    /**
     * @var Target
     */
    public $target;

    /**
     * @var Result[]
     */
    public $results = array();
}
