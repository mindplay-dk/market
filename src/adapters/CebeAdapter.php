<?php

namespace mindplay\market\adapters;

use mindplay\market\Adapter;

use cebe\markdown\Parser;
use cebe\markdown\Markdown;
use cebe\markdown\MarkdownExtra;
use cebe\markdown\GithubMarkdown;

class CebeAdapter implements Adapter
{
    /**
     * @var Parser
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
     * @return self
     */
    public static function github()
    {
        return new self(new GithubMarkdown());
    }

    /**
     * @param Parser|null $implementation
     */
    protected function __construct(Parser $implementation = null)
    {
        $this->parser = $implementation;
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
