<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/3 下午10:01
 */

require '../vendor/autoload.php';

use Swlib\Http\Message;

$message = new Message();
$message->withHeader('Host', 'www.qq.com');
$message->withHeader('DNT', '1');
$message->withAddedHeader('Content-Type', 'text/plain');
$message->withAddedHeader('Accept', ['application/json', 'application/javascript']);
$message->withoutHeader('host');
var_dump($message->getHeader('dnt'));
var_dump($message->getHeaderLine('accept'));
var_dump($message->getHeaders(true,true));