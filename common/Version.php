<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-26
 * Time: 21:17
 */

namespace Common;


class Version
{
    const version = '0.2.0';

    public static function getVersion()
    {
        return Version\version;
    }
}