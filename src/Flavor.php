<?php

namespace mindplay\market;

/**
 * Flavor defines the scope of a {@see Target} or {@see Test}
 */
abstract class Flavor
{
    const VANILLA = 'VANILLA';
    const EXTRA = 'EXTRA';
    const GITHUB = 'GITHUB';
    const COMMON = 'COMMON';
    const OTHER = 'OTHER';

    public static $ALL = array(
        self::VANILLA,
        self::EXTRA,
        self::GITHUB,
        self::COMMON,
        self::OTHER,
    );
}
