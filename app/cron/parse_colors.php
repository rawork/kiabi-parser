#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';
$colors = [];
if (file_exists(YANDEX_COLORS_PATH)) {
	$colors = json_decode(file_get_contents(YANDEX_COLORS_PATH), true);
}

$parser = new Kiabi\ColorParser(new \Kiabi\Replacer());

$parser->parse();

$colors = array_merge($parser->getColors(), $colors);

@unlink(YANDEX_COLORS_PATH);
@file_put_contents(YANDEX_COLORS_PATH, json_encode($colors));