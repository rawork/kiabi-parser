#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__.'/../bootstrap.php';

$parser = new Kiabi\ColorParser(new \Kiabi\Replacer());

$parser->parse();

@unlink(YANDEX_COLORS_PATH);
@file_put_contents(YANDEX_COLORS_PATH, $parser->getJson());