#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\Yandex0Parser(
    FEED_YANDEX_PATH,
    YANDEX_CATEGORIES_PATH,
    LINK_COUNTER_APPENDIX_YANDEX,
	new \Kiabi\Cutter([]),
	new \Kiabi\Replacer(json_decode(file_get_contents(YANDEX_COLORS_PATH), true))
);

$parser->parse();

@unlink(FEED_CONVERTED_YANDEX0_PATH);
@file_put_contents(FEED_CONVERTED_YANDEX0_PATH, $parser->getXML());