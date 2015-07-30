<?php

namespace mindplay\market\adapters;

use mindplay\market\Adapter;

use Michelf\MarkdownInterface;
use Michelf\Markdown;
use Michelf\MarkdownExtra;

class MichelfAdapter implements Adapter
{
    /**
     * @var MarkdownInterface
     */
    private $parser;

    /**
     * @return self
     */
    public static function vanilla()
    {
        return new self(new Markdown());
    }

    /**
     * @return self
     */
    public static function extra()
    {
        return new self(new MarkdownExtra());
    }

    /**
     * @param MarkdownInterface $implementation
     */
    protected function __construct(MarkdownInterface $implementation)
    {
        $this->parser = $implementation;
    }

    /**
     * @inheritdoc
     */
    public function parse($input)
    {
        return $this->parser->transform($input);
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return get_class($this->parser);
    }
}
