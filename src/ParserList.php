<?php

namespace mindplay\market;

class ParserList
{
    /**
     * @var Parser
     */
    private $parsers = array();

    public function register(Parser $parser)
    {
        $this->parsers = $parser;
    }
}
