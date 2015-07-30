<?php

namespace mindplay\market;

interface Parser
{
    /**
     * @param string $input
     *
     * @return string output
     */
    public function parse($input);

    /**
     * @return string Markdown parser class-name
     */
    public function getClassName();
}
