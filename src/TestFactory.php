<?php

namespace mindplay\market;

class TestFactory
{
    /**
     * @var string
     */
    private $vendor_path;

    /**
     * @param string $vendor_path absolute path to Composer "vendor" folder
     */
    public function __construct($vendor_path)
    {
        $this->vendor_path = $vendor_path;
    }

    /**
     * @param string $rel_path relative path to test files (from vendor root)
     * @param string $md_ext markdown file extension
     * @param string $html_ext HTML file extension
     *
     * @return Test[]
     */
    public function createTests($rel_path, $md_ext = 'md', $html_ext = 'html')
    {
        $paths = glob("{$this->vendor_path}/{$rel_path}/*.{$md_ext}");

        $tests = array();

        foreach ($paths as $path) {
            $test = new Test();

            $test->reference = substr($path, strlen($this->vendor_path) + 1) . '|' . $html_ext;
            $test->input = file_get_contents($path);
            $test->expected = file_get_contents(substr($path, 0, -strlen($md_ext)) . $html_ext);

            $tests[] = $test;
        }

        return $tests;
    }
}
