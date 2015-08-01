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

    /**
     * @return string base filename for this group of results
     */
    public function getBaseName()
    {
        $name = implode('_', array($this->target->package_name, $this->target->version, $this->target->flavor));

        return preg_replace('#[^\w_]+#', '_', $name);
    }
}
