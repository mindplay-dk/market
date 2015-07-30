<?php

namespace mindplay\market\adapters;

use mindplay\market\Adapter;

use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

class CiconiaAdapter implements Adapter
{
    /**
     * @var Ciconia
     */
    private $parser;

    /**
     * @return self
     */
    public static function vanilla()
    {
        return new self(new Ciconia());
    }

    /**
     * @return self
     */
    public static function github()
    {
        $parser = new Ciconia();

        $parser->addExtension(new Gfm\FencedCodeBlockExtension());
        $parser->addExtension(new Gfm\TaskListExtension());
        $parser->addExtension(new Gfm\InlineStyleExtension());
        $parser->addExtension(new Gfm\WhiteSpaceExtension());
        $parser->addExtension(new Gfm\TableExtension());
        $parser->addExtension(new Gfm\UrlAutoLinkExtension());

        return new self($parser);
    }

    protected function __construct(Ciconia $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @inheritdoc
     */
    public function parse($input)
    {
        return $this->parser->render($input);
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return get_class($this->parser);
    }
}