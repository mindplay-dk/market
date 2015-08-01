<?php

namespace mindplay\market;

use RuntimeException;

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
     * @param string $flavor   Markdown flavor
     *
     * @param string $md_ext   markdown file extension
     * @param string $html_ext HTML file extension
     * @return Test[]
     */
    public function createTests($rel_path, $flavor, $md_ext = 'md', $html_ext = 'html')
    {
        $pattern = "{$this->vendor_path}/{$rel_path}/*.{$md_ext}";

        $md_paths = glob($pattern);

        $tests = array();

        foreach ($md_paths as $md_path) {
            $test = new Test();

            $html_path = substr($md_path, 0, -strlen($md_ext)) . $html_ext;

            if (!file_exists($html_path)) {
                throw new RuntimeException("file not found: {$html_path}");
            }

            $test->reference = substr($md_path, strlen($this->vendor_path) + 1) . '|' . $html_ext;
            $test->input = file_get_contents($md_path);
            $test->expected = file_get_contents($html_path);
            $test->flavor = $flavor;

            $tests[] = $test;
        }

        if (count($tests) === 0) {
            throw new RuntimeException("no tests found matching pattern: {$pattern}");
        }

        return $tests;
    }
}
