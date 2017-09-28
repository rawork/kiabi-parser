#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\GoogleParser(FEED_YANDEX_PATH);

$parser->parse();

@unlink(FEED_CONVERTED_GOOGLE_PATH);
@file_put_contents(FEED_CONVERTED_GOOGLE_PATH, $parser->getXML());