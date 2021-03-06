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
     * Create a list of tests from a set of test-files.
     *
     * @param string          $rel_path relative path to test files (from vendor root)
     * @param string          $flavor   Markdown flavor
     * @param string          $md_ext   markdown file extension
     * @param string|string[] $html_ext one or more possible HTML file extensions
     *
     * @return Test[]
     */
    public function fromFiles($rel_path, $flavor, $md_ext = 'md', $html_ext = 'html')
    {
        $pattern = "{$this->vendor_path}/{$rel_path}/*.{$md_ext}";

        $md_paths = glob($pattern);

        $tests = array();

        $html_exts = (array)$html_ext;

        foreach ($md_paths as $md_path) {
            $found = false;

            foreach ($html_exts as $html_ext) {
                $html_path = substr($md_path, 0, -strlen($md_ext)) . $html_ext;

                if (!file_exists($html_path)) {
                    continue;
                }

                $reference = substr($md_path, strlen($this->vendor_path) + 1) . '|' . $html_ext;
                $input = file_get_contents($md_path);
                $expected = file_get_contents($html_path);

                $tests[] = new Test($reference, $input, $expected, $flavor);

                $found = true;
            }

            if (!$found) {
                throw new RuntimeException("no matching file found for: {$md_path}"
                    . " (tried extensions: " . implode(', ', $html_exts) . ")");
            }
        }

        if (count($tests) === 0) {
            throw new RuntimeException("no tests found matching pattern: {$pattern}");
        }

        return $tests;
    }

    /**
     * Derive a list of tests using a given Target as the source of truth.
     *
     * @param Target $target
     * @param Test[] $tests list of tests from which to derive new tests
     *
     * @return Test[]
     */
    public function fromTarget(Target $target, $tests)
    {
        return array_map(
            function (Test $test) use ($target) {
                $result = Suite::test($test, $target);

                return new Test(
                    "{$target->package_name}/{$target->version}/{$target->flavor}#{$test->reference}",
                    $test->input,
                    $result->output,
                    $target->flavor
                );
            },
            $tests
        );
    }

    /**
     * Create a list of tests from the CommonMark specification
     *
     * https://github.com/jgm/CommonMark/blob/master/spec.txt
     *
     * @param string $path absolute path to commonmark spec ("spec.txt")
     * @param string $flavor Markdown flavor
     *
     * @return Test[]
     */
    public function fromSpec($path, $flavor)
    {
        $f = fopen($path, 'r');

        $markdown = '';
        $html = '';
        $index = 0;
        $filename = substr($path, strlen($this->vendor_path) + 1);

        static $WAITING = 0;
        static $IN_MARKDOWN = 1;
        static $IN_HTML = 2;

        $state = $WAITING;

        $tests = array();

        while (!feof($f)) {
            $line = str_replace("\r\n", "\n", fgets($f));

            $is_marker = ($line === ".\n");

            switch ($state) {
                case $WAITING:
                    if ($is_marker) {
                        $state = $IN_MARKDOWN;
                    }
                    break;

                case $IN_MARKDOWN:
                    if ($is_marker) {
                        $state = $IN_HTML;
                    } else {
                        $markdown .= $line;
                    }
                    break;

                case $IN_HTML:
                    if ($is_marker) {
                        $index += 1;

                        $tests[] = new Test($filename . '#' . $index, $markdown, $html, $flavor);

                        $markdown = '';
                        $html = '';

                        $state = $WAITING;
                    } else {
                        $html .= $line;
                    }
                    break;

                default:
                    throw new RuntimeException("internal error - undefined state: {$state}");
            }
        }

        if (count($tests) === 0) {
            throw new RuntimeException("no markers found in specification: {$path}");
        }

        return $tests;
    }
}
