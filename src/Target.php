<?php

namespace mindplay\market;

class Target
{
    /**
     * @var Adapter
     */
    public $adapter;

    /**
     * Markdown flavor: one of the {@see Flavor} constants
     *
     * @var string
     */
    public $flavor;

    /**
     * @var string parser implementation class-name
     */
    public $class_name;

    /**
     * @var string composer package name
     */
    public $package_name;

    /**
     * @var string absolute package root path
     */
    public $package_path;

    /**
     * @var string package version
     */
    public $version;

    /**
     * @var string package timestamp
     */
    public $time;
}
