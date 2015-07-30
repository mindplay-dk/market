<?php

namespace mindplay\market;

class Target
{
    /** @var Adapter */
    public $adapter;

    /** @var string target description */
    public $description;

    /** @var string parser implementation class-name */
    public $class_name;

    /** @var string composer package name */
    public $package_name;

    /** @var string absolute package root path */
    public $package_path;

    /** @var string package version */
    public $version;

    /** @var string package timestamp */
    public $time;
}
