<?php

namespace mindplay\market\adapters;

use mindplay\market\Adapter;

use Parsedown;
use ParsedownExtra;

class ErusevAdapter implements Adapter
{
    /**
     * @var Parsedown
     */
    private $parser;

    /**
     * @return self
     */
    public static function vanilla()
    {
        return new self(new Parsedown());
    }

    /**
     * @return self
     */
    public static function extra()
    {
        return new self(new ParsedownExtra());
    }

    /**
     * @param Parsedown|null $implementation
     */
    protected function __construct(Parsedown $implementation)
    {
        $this->parser = $implementation;
    }

    /**
     * @inheritdoc
     */
    public function parse($input)
    {
        return $this->parser->text($input);
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return get_class($this->parser);
    }
}
