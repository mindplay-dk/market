<?php

namespace mindplay\market\adapters;

use mindplay\market\Adapter;

use League\CommonMark\Converter;
use League\CommonMark\CommonMarkConverter;

class CommonMarkAdapter implements Adapter
{
    /**
     * @var Converter
     */
    private $parser;

    /**
     * @return self
     */
    public static function vanilla()
    {
        return new self(new CommonMarkConverter());
    }

    /**
     * @param Converter $implementation
     */
    protected function __construct(Converter $implementation = null)
    {
        $this->parser = $implementation;
    }

    /**
     * @inheritdoc
     */
    public function parse($input)
    {
        return $this->parser->convertToHtml($input);
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return get_class($this->parser);
    }
}
