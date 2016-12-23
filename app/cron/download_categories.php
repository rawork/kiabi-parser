#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$client = new \Kiabi\Curl();
$client->get(MAINPAGE_URL);

$startPos = strpos($client->response, '<ul class="menu">');
$endPos = strpos($client->response, '<!-- hit: Вакансии 0 -->');

file_put_contents(MAINPAGE_PATH, substr($client->response, $startPos, $endPos-$startPos).'</ul>');

$client->close();
