<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/19 下午10:48
 */

use Swlib\Http\ContentType;

require '../vendor/autoload.php';

echo ContentType::get('png') . "\n";
echo ContentType::get('jpg') . "\n";
echo ContentType::get('exe') . "\n";
echo ContentType::get('css') . "\n";
echo ContentType::get('js') . "\n";
echo ContentType::get('html') . "\n";
echo ContentType::get('txt') . "\n";