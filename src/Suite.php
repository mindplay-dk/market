<?php

namespace mindplay\market;

class Suite
{
    /**
     * @var Target
     */
    public $targets = array();

    /**
     * @param Target[] $targets
     */
    public function __construct($targets)
    {
        $this->targets = $targets;
    }
}
