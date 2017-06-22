#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\AvitoDividedParser(
	new \Kiabi\Cutter([]),
	new \Kiabi\Replacer(json_decode(file_get_contents(YANDEX_COLORS_PATH), true))
);

$parser->parse();

$feeds = $parser->getXML();

$pathParts = pathinfo(FEED_CONVERTED_AVITO_PATH);

foreach ($feeds as $feed) {
	@unlink($pathParts['dirname']  .'/'. $pathParts['filename'] . '_' . $feed['id']. '.' . $pathParts['extension']);
	@file_put_contents($pathParts['dirname']  .'/'. $pathParts['filename'] . '_' . $feed['id']. '.' . $pathParts['extension'], $feed['content']);
}
