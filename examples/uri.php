<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/25 下午1:56
 */

require '../vendor/autoload.php';

use Swlib\Http\Uri;

$uri = new Uri('https://swlib:123@www.qq.com/news/index.html?q=1#section1');
var_dump($uri->getScheme());
var_dump($uri->getHost());
var_dump($uri->getPort());
var_dump($uri->getDir());
var_dump($uri->getPath());
var_dump($uri->getQuery());
var_dump($uri->getUserInfo());
var_dump($uri->getFragment());
var_dump($uri->getPathWithQuery());
$uri->withQuery(['w' => '2']);
var_dump((string)$uri);

$uri = Uri::resolve($uri, 'hot');
var_dump((string)$uri);