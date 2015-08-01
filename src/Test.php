<?php

namespace mindplay\market;

class Test
{
    /**
     * @var string test source reference
     */
    public $reference;

    /**
     * @var string Markdown input
     */
    public $input;

    /**
     * @var string expected HTML output
     */
    public $expected;

    /**
     * One of the {@see Flavor} constants
     *
     * @var string
     */
    public $flavor;

    /**
     * @param string $reference
     * @param string $input
     * @param string $expected
     * @param string $flavor
     */
    public function __construct($reference, $input, $expected, $flavor)
    {
        $this->reference = $reference;
        $this->input = $input;
        $this->expected = $expected;
        $this->flavor = $flavor;
    }
}
