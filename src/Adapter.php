<?php

namespace mindplay\market;

interface Adapter
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
