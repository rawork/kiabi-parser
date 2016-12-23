#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\YandexParser();

$parser->parse();

@unlink(FEED_CONVERTED_YANDEX_PATH);
@file_put_contents(FEED_CONVERTED_YANDEX_PATH, $parser->getXML());