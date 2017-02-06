#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$categories = [];
if (file_exists(YANDEX_CATEGORIES_PATH)) {
	$categories = json_decode(file_get_contents(YANDEX_CATEGORIES_PATH), true);
}

$parser = new Kiabi\YandexCategoryParser();

$parser->parse();

@unlink(YANDEX_CATEGORIES_PATH);
@file_put_contents(YANDEX_CATEGORIES_PATH, $parser->getJson());
