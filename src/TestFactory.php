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
     * @param string $rel_path relative path to test files (from vendor root)
     * @param string $flavor   Markdown flavor
     * @param string $md_ext   markdown file extension
     * @param string $html_ext HTML file extension
     *
     * @return Test[]
     */
    public function fromFiles($rel_path, $flavor, $md_ext = 'md', $html_ext = 'html')
    {
        $pattern = "{$this->vendor_path}/{$rel_path}/*.{$md_ext}";

        $md_paths = glob($pattern);

        $tests = array();

        foreach ($md_paths as $md_path) {
            $html_path = substr($md_path, 0, -strlen($md_ext)) . $html_ext;

            if (!file_exists($html_path)) {
                throw new RuntimeException("file not found: {$html_path}");
            }

            $reference = substr($md_path, strlen($this->vendor_path) + 1) . '|' . $html_ext;
            $input = file_get_contents($md_path);
            $expected = file_get_contents($html_path);

            $tests[] = new Test($reference, $input, $expected, $flavor);
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
}
