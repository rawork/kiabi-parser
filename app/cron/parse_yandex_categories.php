#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\YandexCategoryParser();

$parser->parse();

@unlink(YANDEX_CATEGORIES_PATH);
@file_put_contents(YANDEX_CATEGORIES_PATH, $parser->getJson());
