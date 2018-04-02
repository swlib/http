<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/26 下午1:07
 */

require '../vendor/autoload.php';

use Swlib\Http\Util;

$header = Util::parseHeader(
    "HTTP/1.1 200 OK\n" .
    "Date: Mon, 26 Mar 2018 05:03:28 GMT"
);
var_dump($header);