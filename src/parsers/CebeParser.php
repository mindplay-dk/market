<?php

namespace mindplay\market\parsers;

use mindplay\market\Parser;
use cebe\markdown\Markdown;

class CebeParser implements Parser
{
    private $parser;

    public function __construct()
    {
        $this->parser = new Markdown();
    }

    /**
     * @inheritdoc
     */
    public function parse($input)
    {
        return $this->parser->parse($input);
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return get_class($this->parser);
    }
}
